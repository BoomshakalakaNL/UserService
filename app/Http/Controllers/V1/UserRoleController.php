<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

use App\Transformers\RoleTransformer;
use App\Transformers\UserTransformer;
use Validator;
use Dingo\Api\Routing\Helpers;
use Laravel\Lumen\Routing\Controller as BaseController;

class UserRoleController extends BaseController
{
    use Helpers;

    private $validationRulesUser = [
        'role_id' => 'required|uuid|exists:roles,id'
    ];

    private $validationRulesRole = [
        'user_id' => 'required|uuid|exists:users,id'
    ];
    
    public function __construct(\App\User $user, \App\Role $role, UserTransformer $userTransformer, RoleTransformer $roleTransformer)
    {
        $this->user = $user;
        $this->role = $role;
        $this->userTransformer = $userTransformer;
        $this->roleTransformer = $roleTransformer;
    }

    /** 
     * Start extention Users
     * api/v1/USERS/<id>/roles
    */

    public function indexUser($user)
    {
        $user = $this->user->find($user);
        if(!$user)
        {
            return new JsonResponse([
                'errors' => 'The specified user hasn\'t been found'
            ], Response::HTTP_NOT_FOUND);
        }
        $roles = $user->roles;
        return $this->response->array($roles, $this->roleTransformer);
    }

    /**
     * Attach a new role to a user
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $user
     * @return \Illuminate\Http\Response
     */
    public function storeUser(Request $request, $user)
    {
        $user = $this->user->find($user);
        if(!$user)
        {
            return new JsonResponse([
                'errors' => 'The specified user hasn\'t been found'
            ], Response::HTTP_NOT_FOUND);
        }

        $input = $request->all();
        $validator = Validator::make($input, $this->validationRulesUser);
        if ($validator->fails())
        {
            return new JsonResponse([
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        $role = $this->role->find($input['role_id']);
        $user->roles()->attach($role);
        return $this->response->array($user->roles, $this->roleTransformer);
    }

    public function destroyUser(Request $request, $user, $role)
    {
        $user = $this->user->find($user);
        if(!$user)
        {
            return new JsonResponse([
                'errors' => 'The specified user hasn\'t been found'
            ], Response::HTTP_NOT_FOUND);
        }

        $role = $this->role->find($role);
        if(!$role)
        {
            return new JsonResponse([
                'errors' => 'The specified role hasn\'t been found'
            ], Response::HTTP_NOT_FOUND);
        }
        
        $user->roles()->detach($role);
        return new JsonResponse([
            'message' => 'The role has been succesfully detached from this user.',
            'user_id' => $user->id,
            'role_id' => $role->id
        ], Response::HTTP_OK);
    }

    /** 
     * Start extention Roles
     * api/v1/roles/<id>/users
    */

    public function indexRole($role)
    {
        $role = $this->role->find($role);
        if(!$role)
        {
            return new JsonResponse([
                'errors' => 'The specified role hasn\'t been found'
            ], Response::HTTP_NOT_FOUND);
        }

        $users = $role->users;
        return $this->response->array($users, $this->userTransformer);
    }

    /**
     * Attach a new user to a role
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $role
     * @return \Illuminate\Http\Response
     */
    public function storeRole(Request $request, $role)
    {
        $role = $this->role->find($role);
        if(!$role)
        {
            return new JsonResponse([
                'errors' => 'The specified role hasn\'t been found'
            ], Response::HTTP_NOT_FOUND);
        }

        $input = $request->all();
        $validator = Validator::make($input, $this->validationRulesRole);
        if ($validator->fails())
        {
            return new JsonResponse([
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        $user = $this->user->find($input['user_id']);
        $role->users()->attach($user);
        return $this->response->array($role->users, $this->userTransformer);
    }

    public function destroyRole(Request $request, $role, $user)
    {
        $role = $this->role->find($role);
        if(!$role)
        {
            return new JsonResponse([
                'errors' => 'The specified role hasn\'t been found'
            ], Response::HTTP_NOT_FOUND);
        }

        $user = $this->user->find($user);
        if(!$user)
        {
            return new JsonResponse([
                'errors' => 'The specified user hasn\'t been found'
            ], Response::HTTP_NOT_FOUND);
        }
        
        $role->users()->detach($user);
        return new JsonResponse([
            'message' => 'The user has been succesfully detached from this role.',
            'role_id' => $role->id,
            'user_id' => $user->id
        ], Response::HTTP_OK);
    }

    
}