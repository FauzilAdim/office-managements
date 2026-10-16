<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class HierarchyService
{
    /**
     * Get users that can be assigned tasks by the given user.
     *
     * @return Collection<int, User>
     */
    public function getAssignableUsers(User $user): Collection
    {
        if ($user->isDirector()) {
            return User::query()
                ->where('id', '!=', $user->id)
                ->where('is_active', true)
                ->with(['division', 'roles'])
                ->orderBy('name')
                ->get();
        }

        if ($user->isVP()) {
            return User::query()
                ->where('id', '!=', $user->id)
                ->where('division_id', $user->division_id)
                ->where('is_active', true)
                ->with(['division', 'roles'])
                ->orderBy('name')
                ->get();
        }

        if ($user->isManager() || $user->isTeamLead()) {
            $subordinateIds = $user->allSubordinateIds();

            return User::query()
                ->whereIn('id', $subordinateIds)
                ->where('is_active', true)
                ->with(['division', 'roles'])
                ->orderBy('name')
                ->get();
        }

        return new Collection;
    }

    /**
     * Get users visible to the given user based on role hierarchy.
     *
     * @return Collection<int, User>
     */
    public function getVisibleUsers(User $user): Collection
    {
        if ($user->isDirector()) {
            return User::query()
                ->with(['division', 'roles', 'supervisor'])
                ->orderBy('name')
                ->get();
        }

        if ($user->isVP()) {
            return User::query()
                ->where('division_id', $user->division_id)
                ->with(['division', 'roles', 'supervisor'])
                ->orderBy('name')
                ->get();
        }

        if ($user->isManager() || $user->isTeamLead()) {
            $subordinateIds = array_merge([$user->id], $user->allSubordinateIds());

            return User::query()
                ->whereIn('id', $subordinateIds)
                ->with(['division', 'roles', 'supervisor'])
                ->orderBy('name')
                ->get();
        }

        return new Collection([$user->load(['division', 'roles', 'supervisor'])]);
    }

    /**
     * Check if assigner can assign a task to target.
     */
    public function canAssignTo(User $assigner, User $target): bool
    {
        if ($assigner->id === $target->id) {
            return true; // Self-assigned task
        }

        if ($assigner->isDirector()) {
            return true;
        }

        if ($assigner->isVP()) {
            return $target->division_id === $assigner->division_id;
        }

        if ($assigner->isManager() || $assigner->isTeamLead()) {
            return in_array($target->id, $assigner->allSubordinateIds(), true);
        }

        return false;
    }

    /**
     * Build nested tree of organizational structure for visualization.
     *
     * @return array<int, array<string, mixed>>
     */
    public function buildOrganizationTree(): array
    {
        // Root users are users without supervisors (usually Director)
        $rootUsers = User::query()
            ->whereNull('supervisor_id')
            ->where('is_active', true)
            ->with(['roles', 'division', 'subordinates' => function ($query) {
                $query->with(['roles', 'division'])->orderBy('name');
            }])
            ->get();

        return $rootUsers->map(fn (User $user) => $this->formatUserNode($user))->toArray();
    }

    /**
     * Format a user and recursively attach their subordinates.
     *
     * @return array<string, mixed>
     */
    private function formatUserNode(User $user): array
    {
        $subordinates = $user->subordinates()
            ->where('is_active', true)
            ->with(['roles', 'division'])
            ->orderBy('name')
            ->get();

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'position' => $user->position ?? 'Staff',
            'role' => $user->roleDisplayName(),
            'division' => $user->division?->name ?? 'Head Office',
            'division_code' => $user->division?->code ?? 'HQ',
            'avatar' => $user->avatarUrl(),
            'active_task' => $user->currentActiveTask()?->title,
            'children' => $subordinates->map(fn (User $child) => $this->formatUserNode($child))->toArray(),
        ];
    }
}
