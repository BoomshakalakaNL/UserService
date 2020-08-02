<?php

namespace App\Triats;

use Illuminate\Support\Facades\Hash;

trait UsesHashPassword
{
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function getPasswordAttribute($value)
    {
        return $value;
    }
}