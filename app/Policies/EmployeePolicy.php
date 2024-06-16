<?php

namespace App\Policies;

use App\Models\User;

class EmployeePolicy
{
    public function before(?User $user, string $ability): bool|null
    {
        if ($ability == 'updateAdmin') {
            return null;
        }
        if ($user?->admin) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->type == 'A';
    }

    public function view(User $user, User $employee): bool
    {
        return $user->type == 'A';
    }

    public function create(User $user): bool
    {
        return true;

        /*TODO
        return false;*/
    }

    public function update(User $user, User $employee): bool
    {
        return $user->type == 'A' || $user->id == $employee->id;
    }

    public function createEmployee(User $user): bool
    {
        return $user->type == 'A';
    }

    public function updateEmployee(User $user, User $employee): bool
    {
        return $user->type == 'A' && $user->id == $employee->id;
    }

    public function delete(User $user, User $employee): bool
    {
        return true;


    }

}
