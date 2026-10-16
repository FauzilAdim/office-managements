<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        if ($user->isDirector()) {
            return true;
        }

        if ($user->id === $task->assigned_to || $user->id === $task->assigned_by) {
            return true;
        }

        if ($user->isVP()) {
            return $task->assignee?->division_id === $user->division_id;
        }

        if ($user->isManager() || $user->isTeamLead()) {
            $subordinates = $user->allSubordinateIds();

            return in_array($task->assigned_to, $subordinates, true);
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Staff cannot assign tasks; Team Lead, Manager, VP, Director can
        return ! $user->isStaff();
    }

    /**
     * Determine whether the user can update the model details.
     */
    public function update(User $user, Task $task): bool
    {
        if ($user->isDirector()) {
            return true;
        }

        if ($user->id === $task->assigned_by) {
            return true;
        }

        if ($user->isVP() && $task->assignee?->division_id === $user->division_id) {
            return true;
        }

        if ($user->isManager() && in_array($task->assigned_to, $user->allSubordinateIds(), true)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update task status/progress.
     */
    public function updateStatus(User $user, Task $task): bool
    {
        if ($user->id === $task->assigned_to || $user->id === $task->assigned_by) {
            return true;
        }

        if ($user->isDirector()) {
            return true;
        }

        if ($user->isVP() && $task->assignee?->division_id === $user->division_id) {
            return true;
        }

        if (($user->isManager() || $user->isTeamLead()) && in_array($task->assigned_to, $user->allSubordinateIds(), true)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        return $user->isDirector() || $user->id === $task->assigned_by;
    }
}
