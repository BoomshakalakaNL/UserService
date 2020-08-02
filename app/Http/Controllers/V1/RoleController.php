<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

use App\Transformers\RoleTransformer;
use Validator;
use Dingo\Api\Routing\Helpers;
use Laravel\Lumen\Routing\Controller as BaseController;

class RoleController extends BaseController
{
    use Helpers;

    private $validationRules = [
        'name' => 'required|unique:roles|min:3'
    ];

    public function __construct(\App\Role $role, RoleTransformer $roleTransformer)
    {
        $this->role = $role;
        $this->transformer = $roleTransformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = $this->role->paginate(50);
        return $this->response->paginator($roles, $this->transformer);
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

        $role = $this->role->create($input);
        return $this->response->item($role, $this->transformer);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $role
     * @return \Illuminate\Http\Response
     */
    public function show($role)
    {
        $role = $this->role->find($role);
        if(!$role)
        {
            return new JsonResponse([
                'errors' => 'The specified role hasn\'t been found'
            ], Response::HTTP_NOT_FOUND);
        }
        return $this->response->item($role, $this->transformer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $role)
    {
        $role = $this->role->find($role);
        if(!$role)
        {
            return new JsonResponse([
                'errors' => 'The specified role hasn\'t been found'
            ], Response::HTTP_NOT_FOUND);
        }

        $input = $request->all();
        $validator = Validator::make($input, $this->validationRules);
        if ($validator->fails())
        {
            return new JsonResponse([
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }
        
        $role->fill($input);
        $role->save();

        return $this->response->item($role, $this->transformer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy($role)
    {
        $role = $this->role->find($role);
        if(!$role)
        {
            return new JsonResponse([
                'errors' => 'The specified role hasn\'t been found'
            ], Response::HTTP_NOT_FOUND);
        }

        $role->delete();
        return new JsonResponse([
            'message' => 'Role has been succesfully deleted.',
            'user_id' => $role->id
        ], Response::HTTP_OK);
    }

}
