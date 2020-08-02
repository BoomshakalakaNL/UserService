<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

/**
 * This class provides a presentation and transformation layer for Role data output.
 */
class RoleTransformer extends TransformerAbstract
{
    public function transform(\App\Role $role)
    {
        return $role->toArray();
    }
}