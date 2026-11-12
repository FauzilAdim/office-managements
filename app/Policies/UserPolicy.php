<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view the target user profile.
     */
    public function view(User $currentUser, User $targetUser): bool
    {
        if ($currentUser->id === $targetUser->id) {
            return true;
        }

        if ($currentUser->hasFullAccess()) {
            return true;
        }

        if ($currentUser->isTeamLead()) {
            return in_array($targetUser->id, $currentUser->allSubordinateIds(), true);
        }

        if ($currentUser->supervisor_id === $targetUser->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the target user profile.
     */
    public function update(User $currentUser, User $targetUser): bool
    {
        return $currentUser->id === $targetUser->id || $currentUser->hasFullAccess();
    }
}
