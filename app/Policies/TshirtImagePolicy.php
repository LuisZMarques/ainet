<?php

namespace App\Policies;

use App\Models\TshirtImage;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TshirtImagePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TshirtImage $tshirtImage): bool
    {
        return $user->isAdmin() || $user->id == $tshirtImage->customer_id;
    }

    public function minhasTshirtImages(User $user): bool
    {
        return $user->isCustomer();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isCustomer();
    }
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TshirtImage $tshirtImage): bool
    {
        return $user->isAdmin() || $user->id == $tshirtImage->customer_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TshirtImage $tshirtImage): bool
    {
        return $user->isAdmin() || $user->id == $tshirtImage->customer_id;
    }
}
