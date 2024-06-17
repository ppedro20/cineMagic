<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TicketPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->type === 'A' || $user->type === 'C'){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Ticket $ticket): bool
    {
        if ($user->type === 'A' || $user->type === 'E' || $user->id === $ticket->purchase->customer_id){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function validate(User $user): bool
    {
        if ($user->type === 'E'){
            return true;
        }
        return false;
    }
}
