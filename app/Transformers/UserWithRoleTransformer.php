<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

/**
 * This class provides a presentation and transformation layer for User data output.
 */
class UserWithRoleTransformer extends TransformerAbstract
{
    public function transform(\App\User $user)
    {
        $response = $user->toArray();
        $response['roles'] = array();
        foreach($user->roles as $role)
        {
            $response['roles'][] = [
                'id' => $role->id,
                'name' => $role->name
            ];
        }

        return $response;
    }
}