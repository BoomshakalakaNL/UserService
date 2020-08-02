<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

use Validator;
use App\Transformers\UserTransformer;
use App\Transformers\UserWithRolesTransformer;
use App\Transformers\UserWithRoleTransformer;
use Dingo\Api\Routing\Helpers;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;

class UserController extends BaseController
{
    use Helpers;

    private $validationRules = [
        'name' => 'sometimes|required|min:3|unique:users',
        'firstName' => 'min:3',
        'lastName' => 'min:3',
        'email' => 'sometimes|required|email',
        'picture' => 'url',
        'password' => 'sometimes|required|min:3'
    ];

    public function __construct(\App\User $user, UserTransformer $userTransformer)
    {
        $this->user = $user;
        $this->transformer = $userTransformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->user->paginate(50);
        return $this->response->paginator($users, $this->transformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, $this->validationRules);
        if ($validator->fails())
        {
            return new JsonResponse([
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        $user = $this->user->create($input);
        return $this->response->item($user, $this->transformer);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $user
     * @return \Illuminate\Http\Response
     */
    public function show($user)
    {
        $user = $this->user->find($user);
        if(!$user)
        {
            return new JsonResponse([
                'errors' => 'The requested user hasn\'t been found'
            ], Response::HTTP_NOT_FOUND);
        }
        return $this->response->item($user, $this->transformer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user)
    {
        $user = $this->user->find($user);
        if(!$user)
        {
            return new JsonResponse([
                'errors' => 'User not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $input = $request->all();
        unset($input['name']);  // username shouldn't update
        unset($this->validationRules['name']);
        $validator = Validator::make($input, $this->validationRules);
        if ($validator->fails())
        {
            return new JsonResponse([
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        $user->fill($input);
        $user->save();

        return $this->response->item($user, $this->transformer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($user)
    {
        $user = $this->user->find($user);
        if(!$user)
        {
            return new JsonResponse([
                'errors' => 'User not found'
            ], Response::HTTP_NOT_FOUND);
        }
        
        $user->delete();
        return new JsonResponse([
            'message' => 'User has been succesfully deleted.',
            'user_id' => $user->id
        ], Response::HTTP_OK);
    }

    /**
     * Check if password matches the specified username in storage,
     * and respond with user model including roles.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $input = $request->all();
        $validationRules = [
            'name' => 'required|min:3|exists:users',
            'password' => 'required'
        ];
        $validator = Validator::make($input, $validationRules);
        if ($validator->fails())
        {
            return new JsonResponse([
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }
        $password = base64_decode($input['password']);

        $user = $this->user->where('name', $input['name'])->first();
        if(!$user)
        {
            return new JsonResponse([
                'errors' => 'This username is not recognized in our system.'
            ], Response::HTTP_NOT_FOUND);
        }

        if(!Hash::check($password, $user->password))
        {
            return new JsonResponse([
                'errors' => 'The given password for this username/email is incorrect.'
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->response->item($user, new UserWithRoleTransformer());
    }
}
