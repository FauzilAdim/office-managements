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
        if ($user->isDirector() || $user->isManager()) {
            return User::query()
                ->where('id', '!=', $user->id)
                ->where('is_active', true)
                ->with(['division', 'roles'])
                ->orderBy('name')
                ->get();
        }

        if ($user->isTeamLead()) {
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
        if ($user->isDirector() || $user->isManager()) {
            return User::query()
                ->with(['division', 'roles', 'supervisor', 'assignedTasks'])
                ->orderBy('name')
                ->get();
        }

        if ($user->isTeamLead()) {
            $subordinateIds = array_merge([$user->id], $user->allSubordinateIds());

            return User::query()
                ->whereIn('id', $subordinateIds)
                ->with(['division', 'roles', 'supervisor', 'assignedTasks'])
                ->orderBy('name')
                ->get();
        }

        return new Collection([$user->load(['division', 'roles', 'supervisor', 'assignedTasks'])]);
    }

    /**
     * Check if assigner can assign a task to target.
     */
    public function canAssignTo(User $assigner, User $target): bool
    {
        if ($assigner->id === $target->id) {
            return true;
        }

        if ($assigner->isDirector() || $assigner->isManager()) {
            return true;
        }

        if ($assigner->isTeamLead()) {
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
