<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class RoleControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testCanGetListOfAllRoles()
    {
        $response = $this->call('GET', '/api/v1/roles');
        $this->assertEquals(200, $response->status());
    }

    public function testCanCreateARole()
    {
        $response = $this->call('POST', '/api/v1/roles', [
            'name' => 'testRole'
        ]);
        $response->assertStatus(200);
    }

    public function testCanReadRole()
    {
        $role = factory('App\Role')->create();
        $response = $this->call('GET', '/api/v1/roles/'.$role->id);
        $response->assertStatus(200);
    }

    public function testCanUpdateRole()
    {
        $role = factory('App\Role')->create();
        $response = $this->call('PUT', '/api/v1/roles/'.$role->id, [
            'name' => 'updatedRoleName'
        ]);
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'updatedRoleName'
            ]);
    }

    public function testCanDeleteRole()
    {
        $role = factory('App\Role')->create();
        $response = $this->call('DELETE', '/api/v1/roles/'.$role->id);
        $this->assertNull(App\Role::find($role->id));
    }
}
