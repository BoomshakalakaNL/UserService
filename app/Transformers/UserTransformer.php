<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

/**
 * This class provides a presentation and transformation layer for User data output.
 */
class UserTransformer extends TransformerAbstract
{
    public function transform(\App\User $user)
    {
        return $user->toArray();
    }
}