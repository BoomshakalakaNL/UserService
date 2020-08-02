<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class UserControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testCanGetListOfAllUsers()
    {
        $response = $this->call('GET', '/api/v1/users');
        $this->assertEquals(200, $response->status());
    }

    public function testCanCreateAUser()
    {
        $response = $this->call('POST', '/api/v1/users', [
            'name' => 'username',
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'admin@verbeek.io',
            'password' => 'password'
        ]);
        $response->assertStatus(200);
    }

    public function testCanReadUser()
    {
        $user = factory('App\User')->create();
        $response = $this->call('GET', '/api/v1/users/'.$user->id);
        $response->assertStatus(200);
    }

    public function testCanUpdateAUser()
    {
        $user = factory('App\User')->create();
        $response = $this->call('PUT', '/api/v1/users/'.$user->id, [
            'firstName' => "UpdatedFirstName"
        ]);
        
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'firstName' => 'UpdatedFirstName',
            ]);
    }
    
    public function testCanNotUpdateUsername()
    {
        $user = factory('App\User')->create();
        $response = $this->call('PUT', '/api/v1/users/'.$user->id, [
            'name' => 'updatedUserName',
            'firstName' => "UpdatedFirstName"
        ]);
        
        $userName = $user->name;

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => $userName,
            ]);
    }

    public function testCanDeleteUser()
    {
        $user = factory('App\User')->create();
        $response = $this->call('DELETE', '/api/v1/users/'.$user->id);
        $this->assertNull(App\User::find($user->id));
    }

    public function testCanLoginUser()
    {
        $user = factory('App\User')->create();
        $user->password = 'password123';
        $user->save();

        $response = $this->call('POST', '/api/v1/users/login', [
            'name' => $user->name,
            'password' => base64_encode('password123')
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $user->id,
                'name' => $user->name,
                'roles' => $user->roles
            ]);
        
    }
}
