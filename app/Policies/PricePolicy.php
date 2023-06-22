<?php

namespace App\Policies;

use App\Models\Price;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PricePolicy
{

    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Price $price): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Price $price): bool
    {
        return $user->isAdmin();
    }
}
