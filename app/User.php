<?php

namespace App;

use App\Triats\UsesHashPassword;
use App\Triats\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use UsesUuid;
    use UsesHashPassword;
    protected $table = "users";

    protected $fillable = [
        'name', 'email', 'firstName', 'lastName', 'picture', 'password'
    ];

    protected $hidden = [
        'password'
    ];

    /**
     * The roles that belong to the user.
     */
    public function roles()
    {
        return $this->belongsToMany('App\Role', 'user_roles');
    }

}
