<?php

use App\Role;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class RoleModelTest extends TestCase
{
    use DatabaseTransactions;
    
    public function createRole()
    {
        return Role::create(['name' => 'TestRole']);
    }

    public function testCanGetSetName()
    {
        $role = Role::make([
            'name' => 'system'
        ]);
        $this->assertEquals('system', $role->name);
        
    }

    public function testCanCreateRoleInDatabase()
    {
        $role = $this->createRole();
        $roleId = $role->id;
        $this->assertNotNull($role);
    }

    public function testCanReadRoleFromDatabase()
    {
        $role = $this->createRole();
        $roleId = $role->id;
        $this->assertNotNull($role);
    }

    public function testCanUpdateRoleFromDatabase()
    {
        $role = $this->createRole();
        $role = Role::find($role->id);
        $role->name = 'TestFieldChange';
        $role->save();
        $this->assertEquals('TestFieldChange', Role::find($role->id)->name);
    }

    public function testCanDeleteRoleFromDatabase()
    {
        $role = $this->createRole();
        $roleId = $role->id;
        $role->delete();
        $this->assertNull(Role::find($roleId));
    }
}
