<?php

namespace App;

use App\Triats\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use UsesUuid;
    
    protected $fillable = [
        'name'
    ];
    
    /**
     * The users that belong to the role.
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'user_roles');
    }
}
