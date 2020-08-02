<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class UserRoleControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testCanGetListOfRolesFromUser()
    {
        $role = factory('App\Role')->create();
        $role->name = 'TestRole';
        $role->save();

        $user = factory('App\User')->create();
        $user->roles()->attach($role);
        $user->save();

        $response = $this->call('GET', '/api/v1/users/'.$user->id.'/roles');
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'TestRole',
            ]);
    }

    public function testCanGetListOfUsersFromRole()
    {
        $user = factory('App\User')->create();
        $user->name = 'TestUser';
        $user->save();

        $role = factory('App\Role')->create();
        $role->users()->attach($user);
        $role->save();

        $response = $this->call('GET', '/api/v1/roles/'.$role->id.'/users');
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'TestUser',
            ]);
    }

    public function testCanAttachRoleToUser()
    {
        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();

        $response = $this->call('POST', '/api/v1/users/'.$user->id.'/roles', [
            'role_id' => $role->id
        ]);
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $role->id,
                'name' => $role->name
            ]);
    }
    
    public function testCanAttachUserToRole()
    {
        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();

        $response = $this->call('POST', '/api/v1/roles/'.$role->id.'/users', [
            'user_id' => $user->id
        ]);
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $user->id,
                'name' => $user->name
            ]);
    }

    public function testCanDeleteAttachedRoleFromUser()
    {
        $role = factory('App\Role')->create();

        $user = factory('App\User')->create();
        $user->roles()->attach($role);

        $response = $this->call('DELETE', '/api/v1/users/'.$user->id.'/roles/'.$role->id);
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'role_id' => $role->id
            ]);
    }

    public function testCanDeleteAttachedUserFromRole()
    {
        $user = factory('App\User')->create();

        $role = factory('App\Role')->create();
        $role->users()->attach($user);

        $response = $this->call('DELETE', '/api/v1/roles/'.$role->id.'/users/'.$user->id);
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'user_id' => $user->id
            ]);
    }
}
