<?php

namespace App\Policies;

use App\Models\User;

class ConfigurationPolicy
{
    public function before(?User $user, string $ability): bool|null
    {
        return null;

        /*TODO
        if ($ability == 'updateAdmin') {
            return null;
        }
        if ($user?->admin) {
            return true;
        }
        return null; */
    }


    public function update(User $user): bool
    {
        return true;

        /*TODO
        return $user->type == 'A' && $user->id == $administrative->id;*/
    }
}
