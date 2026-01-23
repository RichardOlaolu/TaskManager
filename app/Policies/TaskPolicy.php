<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use App\Enums\Role;

class TaskPolicy
{
    /**
     * Determine whether the user can update the task.
     */
    public function update(User $user, Task $task): bool
    {

        // User is always allowed to update their own task
        if ($user->id === $task->created_by) {
            return true;
        }

        // User is NOT the creator - check based on roles
        switch ($task->creator->role) {
            case Role::Admin:
                // Only the specific admin who created it can update
                return false;

            case Role::Lead:
                // Leads: only the specific lead OR any admin can update
                return $user->role === Role::Admin;

            case Role::Member:
                // Members: admins and leads can update member tasks
                return in_array($user->role, [Role::Admin, Role::Lead]);
        }

        return false;
    }

    /**
     * Determine whether the user can view the task.
     * (You might need this too)
     */
    public function view(User $user, Task $task): bool
    {
        // Similar logic for viewing permissions
        if ($user->id === $task->created_by) {
            return true;
        }

        switch ($task->creator->role) {
            case Role::Admin:
                return $user->role === Role::Admin && $user->id === $task->created_by;

            case Role::Lead:
                return in_array($user->role, [Role::Admin, Role::Lead]);
            case Role::Member:
                return in_array($user->role, [Role::Admin, Role::Lead]);
        }

        return false;
    }

    /**
     * Determine whether the user can delete the task.
     */
    // In TaskPolicy.php
public function delete(User $user, Task $task): bool
{
    // User is always allowed to delete their own task
    if ($user->id === $task->created_by) {
        return true;
    }

    // User is NOT the creator - check based on roles
    switch ($task->creator->role) {
            case Role::Admin:
            // Only the specific admin who created it can delete
            return false;

        case Role::Lead:
    // Leads: can delete any task not created by an admin or another lead
    return $user->role === Role::Admin ||
           ($task->creator &&
            $task->creator->role !== Role::Admin &&
            $task->creator->role !== Role::Lead);

        case Role::Member:
            // Members: admins and leads can delete member tasks
            return in_array($user->role, [Role::Admin, Role::Lead]);
    }

    return false;
}
}
