<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * Determine whether the user can view any projects.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the specific project.
     */
    public function view(User $user, Project $project): bool
    {
        if ($user->hasFullAccess()) {
            return true;
        }

        return $project->hasMember($user);
    }

    /**
     * Determine whether the user can create projects.
     */
    public function create(User $user): bool
    {
        return $user->hasFullAccess();
    }

    /**
     * Determine whether the user can update the project.
     */
    public function update(User $user, Project $project): bool
    {
        return $user->hasFullAccess();
    }

    /**
     * Determine whether the user can manage milestones (create/update/delete milestones).
     */
    public function manageMilestones(User $user, Project $project): bool
    {
        if ($user->hasFullAccess()) {
            return true;
        }

        return $user->isTeamLead() && $project->hasMember($user);
    }

    /**
     * Determine whether the user can assign members to the project.
     */
    public function assignMember(User $user, Project $project): bool
    {
        if ($user->hasFullAccess()) {
            return true;
        }

        return $user->isTeamLead() && $project->hasMember($user);
    }

    /**
     * Determine whether the user can delete the project.
     */
    public function delete(User $user, Project $project): bool
    {
        return $user->hasFullAccess();
    }
}

