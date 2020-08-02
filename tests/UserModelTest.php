<?php

use App\User;
use App\Role;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class UserModelTest extends TestCase
{
    use DatabaseTransactions;

    public function createUser()
    {
        return User::create([
            'name' => 'username',
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'test@verbeek.io',
            'picture' => '',
            'password' => Hash::make('password')
        ]);
    }

    public function createRole()
    {
        return Role::create([
            'name' => 'Admin'
        ]);
    }
    
    public function testCanGetSetFirstName()
    {
        $user = User::make([
            'firstName' => 'John'
        ]);
        $this->assertEquals($user->firstName, 'John');
    }

    public function testCanGetSetLastName()
    {
        $user = User::make([
            'lastName' => 'Doe'
        ]);
        $this->assertEquals($user->lastName, 'Doe');
    }

    public function testCanGetSetUsername()
    {
        $user = User::make([
            'name' => 'username'
        ]);
        $this->assertEquals($user->name, 'username');
    }

    public function testCanGetSetEmail()
    {
        $user = User::make([
            'email' => 'test@verbeek.io'
        ]);
        $this->assertEquals($user->email, 'test@verbeek.io');
    }

    public function testCanGetSetPicture()
    {
        $user = User::make([
            'picture' => 'https://www.example.com/pictures/img1.jpg'
        ]);
        $this->assertEquals($user->picture, 'https://www.example.com/pictures/img1.jpg');
    }

    public function testCanGetSetPassword()
    {
        $user = User::make();
        $password = 'password';
        $user->password = $password;
        $this->assertTrue(Hash::check($password, $user->password));
    }

    public function testCanGetSetRole()
    {
        $user = $this->createUser();
        $role = $this->createRole();
        $user->roles()->attach($role);
        $user->save();
        $this->assertEquals('Admin', User::find($user->id)->roles[0]->name);
    }

    public function testCanCreateUserInDatabase()
    {
        $user = User::create([
            'name' => 'username',
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'test@verbeek.io',
            'picture' => '',
            'password' => Hash::make('password')
        ]);
        $dbUser = User::find($user->id);
        $this->assertEquals($user->id, $dbUser->id);
    }

    public function testCanReadUserFromDatabase()
    {
        $user = $this->createUser();
        $userId = $user->id;
        $this->assertNotNull(User::find($userId));
    }

    public function testCanUpdateUserFromDatabase()
    {
        $user = $this->createUser();
        $user->firstName = 'Jane';
        $user->save();
        $this->assertEquals('Jane', $user->firstName);
    }

    public function testCanDeleteUserFromDatabase()
    {
        $user = $this->createUser();
        $userId = $user->id;
        $user->delete();
        $this->assertNull(User::find($userId));
    }
}
