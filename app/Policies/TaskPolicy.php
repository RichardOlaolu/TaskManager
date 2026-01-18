<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

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
        switch ($task->created_by_role) {
            case 'admin':
                // Only the specific admin who created it can update
                return false;

            case 'lead':
                // Leads: only the specific lead OR any admin can update
                return $user->role === 'admin';

            case 'member':
                // Members: admins and leads can update member tasks
                return in_array($user->role, ['admin', 'lead']);
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

        switch ($task->created_by_role) {
            case 'admin':
                return $user->role === 'admin' && $user->id === $task->created_by;

            case 'lead':
                return in_array($user->role, ['admin', 'lead']);

            case 'member':
                return in_array($user->role, ['admin', 'lead']);
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
    switch ($task->created_by_role) {
        case 'admin':
            // Only the specific admin who created it can delete
            return false;

        case 'lead':
            // Leads: only the specific lead OR any admin can delete
            return $user->role === 'admin';

        case 'member':
            // Members: admins and leads can delete member tasks
            return in_array($user->role, ['admin', 'lead']);
    }

    return false;
}
}
