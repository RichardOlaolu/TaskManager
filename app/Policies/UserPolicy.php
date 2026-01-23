<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\Role;

class UserPolicy
{
    /**
     * Determine whether the user can view any users.
     */
    public function viewAny(User $user): bool
    {
        // Only admins can view the user list
        return $user->role === Role::Admin;
    }

    /**
     * Determine whether the user can view the user.
     */
    public function view(User $authUser, User $user): bool
    {
        // Allow admins to view any user, others can only view their own profile
        return $authUser->role === Role::Admin || $authUser->id === $user->id;
    }

    /**
     * Determine whether the user can create users.
     */
    public function create(User $user): bool
    {
        // Only admins can create users
        return $user->role === Role::Admin;
    }

    /**
     * Determine whether the user can update the user.
     */
    public function update(User $authUser, User $user): bool
    {
        // Allow admins to update any user, others can only update their own profile
        return $authUser->role === Role::Admin || $authUser->id === $user->id;
    }

    /**
     * Determine whether the user can delete the user.
     */
    public function delete(User $authUser, User $user): bool
    {
        // Only admins can delete users
        return $authUser->role === Role::Admin;
    }
}
