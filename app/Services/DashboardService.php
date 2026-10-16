<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\TaskStatus;
use App\Models\Division;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class DashboardService
{
    /**
     * Get statistics and dataset for Director dashboard.
     *
     * @return array<string, mixed>
     */
    public function getDirectorDashboardData(): array
    {
        $taskQuery = Task::query();

        $totalTasks = (clone $taskQuery)->count();
        $completedTasks = (clone $taskQuery)->where('status', TaskStatus::Completed->value)->count();
        $inProgressTasks = (clone $taskQuery)->where('status', TaskStatus::InProgress->value)->count();
        $reviewTasks = (clone $taskQuery)->where('status', TaskStatus::Review->value)->count();
        $pendingTasks = (clone $taskQuery)->where('status', TaskStatus::Pending->value)->count();
        $overdueTasks = (clone $taskQuery)->overdue()->count();

        $activeTasks = $inProgressTasks + $reviewTasks + $pendingTasks;
        $completionRate = $totalTasks > 0 ? (int) round(($completedTasks / $totalTasks) * 100) : 0;

        // Divisions breakdown with performance metrics
        $divisions = Division::withCount([
            'tasks as total_tasks',
            'tasks as completed_tasks' => fn (Builder $q) => $q->where('status', TaskStatus::Completed->value),
            'tasks as in_progress_tasks' => fn (Builder $q) => $q->where('status', TaskStatus::InProgress->value),
            'tasks as overdue_tasks' => fn (Builder $q) => $q->where('status', '!=', TaskStatus::Completed->value)->where('deadline', '<', now()),
        ])->with(['users' => fn ($q) => $q->where('is_active', true)])->get();

        // Real-time active employees
        $activeEmployees = $this->getActiveEmployees(null);

        // Recent urgent / priority tasks
        $urgentTasks = Task::query()
            ->with(['assignee.division', 'creator'])
            ->whereIn('priority', ['urgent', 'high'])
            ->where('status', '!=', TaskStatus::Completed->value)
            ->orderBy('deadline')
            ->limit(5)
            ->get();

        $kanbanData = $this->buildKanbanPayload($taskQuery);

        return array_merge([
            'metrics' => [
                'total_tasks' => $totalTasks,
                'active_tasks' => $activeTasks,
                'completed_tasks' => $completedTasks,
                'in_progress_tasks' => $inProgressTasks,
                'review_tasks' => $reviewTasks,
                'pending_tasks' => $pendingTasks,
                'overdue_tasks' => $overdueTasks,
                'completion_rate' => $completionRate,
                'total_employees' => User::where('is_active', true)->count(),
            ],
            'chart_data' => [
                'labels' => ['Belum Dikerjakan', 'Sedang Dikerjakan', 'Review', 'Selesai'],
                'data' => [$pendingTasks, $inProgressTasks, $reviewTasks, $completedTasks],
            ],
            'divisions' => $divisions,
            'active_employees' => $activeEmployees,
            'urgent_tasks' => $urgentTasks,
        ], $kanbanData);
    }

    /**
     * Get statistics and dataset for VP dashboard.
     *
     * @return array<string, mixed>
     */
    public function getVpDashboardData(User $vp): array
    {
        $divisionId = $vp->division_id;

        $taskQuery = Task::whereHas('assignee', fn (Builder $q) => $q->where('division_id', $divisionId));

        $totalTasks = (clone $taskQuery)->count();
        $completedTasks = (clone $taskQuery)->where('status', TaskStatus::Completed->value)->count();
        $inProgressTasks = (clone $taskQuery)->where('status', TaskStatus::InProgress->value)->count();
        $reviewTasks = (clone $taskQuery)->where('status', TaskStatus::Review->value)->count();
        $pendingTasks = (clone $taskQuery)->where('status', TaskStatus::Pending->value)->count();
        $overdueTasks = (clone $taskQuery)->overdue()->count();

        $activeTasks = $inProgressTasks + $reviewTasks + $pendingTasks;
        $completionRate = $totalTasks > 0 ? (int) round(($completedTasks / $totalTasks) * 100) : 0;

        // Managers in division
        $managers = User::role('Manager')
            ->where('division_id', $divisionId)
            ->withCount(['subordinates as team_members_count'])
            ->get();

        // Real-time active employees in this division
        $activeEmployees = $this->getActiveEmployees($divisionId);

        // Division tasks
        $recentTasks = (clone $taskQuery)
            ->with(['assignee', 'creator'])
            ->latest()
            ->limit(7)
            ->get();

        $kanbanData = $this->buildKanbanPayload($taskQuery);

        if ($kanbanData['teamMembers']->count() < 4) {
            $divisionMembers = User::where('division_id', $divisionId)->where('is_active', true)->limit(8)->get();
            $kanbanData['teamMembers'] = $kanbanData['teamMembers']->merge($divisionMembers)->unique('id')->values();
        }

        return array_merge([
            'metrics' => [
                'total_tasks' => $totalTasks,
                'active_tasks' => $activeTasks,
                'completed_tasks' => $completedTasks,
                'in_progress_tasks' => $inProgressTasks,
                'review_tasks' => $reviewTasks,
                'pending_tasks' => $pendingTasks,
                'overdue_tasks' => $overdueTasks,
                'completion_rate' => $completionRate,
                'total_division_employees' => User::where('division_id', $divisionId)->where('is_active', true)->count(),
            ],
            'chart_data' => [
                'labels' => ['Belum Dikerjakan', 'Sedang Dikerjakan', 'Review', 'Selesai'],
                'data' => [$pendingTasks, $inProgressTasks, $reviewTasks, $completedTasks],
            ],
            'division' => $vp->division,
            'managers' => $managers,
            'active_employees' => $activeEmployees,
            'recent_tasks' => $recentTasks,
        ], $kanbanData);
    }

    /**
     * Get statistics and dataset for Manager & Team Lead dashboard.
     *
     * @return array<string, mixed>
     */
    public function getManagerDashboardData(User $manager): array
    {
        $teamMemberIds = array_merge([$manager->id], $manager->allSubordinateIds());

        $taskQuery = Task::whereIn('assigned_to', $teamMemberIds);

        $totalTasks = (clone $taskQuery)->count();
        $completedTasks = (clone $taskQuery)->where('status', TaskStatus::Completed->value)->count();
        $inProgressTasks = (clone $taskQuery)->where('status', TaskStatus::InProgress->value)->count();
        $reviewTasks = (clone $taskQuery)->where('status', TaskStatus::Review->value)->count();
        $pendingTasks = (clone $taskQuery)->where('status', TaskStatus::Pending->value)->count();
        $overdueTasks = (clone $taskQuery)->overdue()->count();

        $activeTasks = $inProgressTasks + $reviewTasks + $pendingTasks;
        $completionRate = $totalTasks > 0 ? (int) round(($completedTasks / $totalTasks) * 100) : 0;

        // Team members status
        $teamMembers = User::whereIn('id', $teamMemberIds)
            ->with(['roles', 'assignedTasks' => function ($q) {
                $q->whereIn('status', [TaskStatus::Pending->value, TaskStatus::InProgress->value, TaskStatus::Review->value]);
            }])
            ->get()
            ->map(function (User $member) {
                $activeTask = $member->assignedTasks
                    ->firstWhere('status', TaskStatus::InProgress);

                return [
                    'user' => $member,
                    'current_task' => $activeTask ? $activeTask->load('creator') : null,
                    'active_tasks_count' => $member->assignedTasks->count(),
                ];
            });

        // Tasks waiting for review
        $reviewList = (clone $taskQuery)
            ->where('status', TaskStatus::Review->value)
            ->with(['assignee', 'creator'])
            ->latest()
            ->get();

        $kanbanData = $this->buildKanbanPayload($taskQuery);

        if ($kanbanData['teamMembers']->count() < 4) {
            $subordinates = User::whereIn('id', $teamMemberIds)->where('is_active', true)->limit(8)->get();
            $kanbanData['teamMembers'] = $kanbanData['teamMembers']->merge($subordinates)->unique('id')->values();
        }

        return array_merge([
            'metrics' => [
                'total_tasks' => $totalTasks,
                'active_tasks' => $activeTasks,
                'completed_tasks' => $completedTasks,
                'in_progress_tasks' => $inProgressTasks,
                'review_tasks' => $reviewTasks,
                'pending_tasks' => $pendingTasks,
                'overdue_tasks' => $overdueTasks,
                'completion_rate' => $completionRate,
                'total_members' => count($teamMemberIds),
            ],
            'chart_data' => [
                'labels' => ['Belum Dikerjakan', 'Sedang Dikerjakan', 'Review', 'Selesai'],
                'data' => [$pendingTasks, $inProgressTasks, $reviewTasks, $completedTasks],
            ],
            'team_members' => $teamMembers,
            'review_list' => $reviewList,
        ], $kanbanData);
    }

    /**
     * Get statistics and dataset for Staff dashboard.
     *
     * @return array<string, mixed>
     */
    public function getStaffDashboardData(User $staff): array
    {
        $taskQuery = Task::where('assigned_to', $staff->id);

        $totalTasks = (clone $taskQuery)->count();
        $completedTasks = (clone $taskQuery)->where('status', TaskStatus::Completed->value)->count();
        $inProgressTasks = (clone $taskQuery)->where('status', TaskStatus::InProgress->value)->count();
        $reviewTasks = (clone $taskQuery)->where('status', TaskStatus::Review->value)->count();
        $pendingTasks = (clone $taskQuery)->where('status', TaskStatus::Pending->value)->count();
        $overdueTasks = (clone $taskQuery)->overdue()->count();

        $currentActiveTask = (clone $taskQuery)
            ->where('status', TaskStatus::InProgress->value)
            ->with('creator')
            ->latest('started_at')
            ->first();

        $myTasks = (clone $taskQuery)
            ->with(['creator', 'logs.user'])
            ->latest()
            ->get();

        $kanbanData = $this->buildKanbanPayload($taskQuery);

        $teamMembers = collect([$staff]);
        if ($staff->supervisor) {
            $teamMembers->push($staff->supervisor);
        }
        $kanbanData['teamMembers'] = $teamMembers;

        return array_merge([
            'metrics' => [
                'total_tasks' => $totalTasks,
                'completed_tasks' => $completedTasks,
                'in_progress_tasks' => $inProgressTasks,
                'review_tasks' => $reviewTasks,
                'pending_tasks' => $pendingTasks,
                'overdue_tasks' => $overdueTasks,
            ],
            'current_active_task' => $currentActiveTask,
            'tasks' => $myTasks,
        ], $kanbanData);
    }

    /**
     * Build kanban dataset and tab statistics for any task query builder.
     *
     * @param  Builder<Task>  $taskQuery
     * @return array<string, mixed>
     */
    private function buildKanbanPayload(Builder $taskQuery): array
    {
        $allTasks = (clone $taskQuery)
            ->with(['assignee.division', 'creator', 'logs'])
            ->orderBy('deadline', 'asc')
            ->get();

        $pendingTasks = $allTasks->filter(fn (Task $t) => $t->status === TaskStatus::Pending)->values();
        $inProgressTasks = $allTasks->filter(fn (Task $t) => $t->status === TaskStatus::InProgress)->values();
        $reviewTasks = $allTasks->filter(fn (Task $t) => $t->status === TaskStatus::Review)->values();
        $completedTasks = $allTasks->filter(fn (Task $t) => $t->status === TaskStatus::Completed)->values();

        // Team members involved in these tasks
        $teamMembers = $allTasks->map(fn (Task $t) => $t->assignee)
            ->filter()
            ->unique('id')
            ->values();

        // Ensure at least some active team members are shown in avatar stack if tasks have few assignees
        if ($teamMembers->count() < 4) {
            $extraMembers = User::where('is_active', true)->limit(8)->get();
            $teamMembers = $teamMembers->merge($extraMembers)->unique('id')->values();
        }

        // Tab counts
        $totalTasksCount = $allTasks->count();
        $highPriorityCount = $allTasks->filter(fn (Task $t) => in_array($t->priority->value, ['high', 'urgent'], true))->count();
        $monitoredCount = $reviewTasks->count();
        $completedMonthCount = $allTasks->filter(function (Task $t) {
            if ($t->status !== TaskStatus::Completed) {
                return false;
            }
            $date = $t->completed_at ?? $t->updated_at;

            return $date && $date->isCurrentMonth();
        })->count();

        return [
            'tasks' => $allTasks,
            'pendingTasks' => $pendingTasks,
            'inProgressTasks' => $inProgressTasks,
            'reviewTasks' => $reviewTasks,
            'completedTasks' => $completedTasks,
            'teamMembers' => $teamMembers,
            'totalTasksCount' => $totalTasksCount,
            'highPriorityCount' => $highPriorityCount,
            'monitoredCount' => $monitoredCount,
            'completedMonthCount' => $completedMonthCount,
        ];
    }

    /**
     * Get real-time employees who are currently in progress on tasks.
     *
     * @return Collection<int, User>
     */
    private function getActiveEmployees(?int $divisionId): Collection
    {
        $query = User::query()
            ->where('is_active', true)
            ->whereHas('assignedTasks', function (Builder $q) {
                $q->where('status', TaskStatus::InProgress->value);
            })
            ->with([
                'division',
                'roles',
                'assignedTasks' => function ($q) {
                    $q->where('status', TaskStatus::InProgress->value)
                        ->with('creator')
                        ->latest('started_at');
                },
            ]);

        if ($divisionId !== null) {
            $query->where('division_id', $divisionId);
        }

        return $query->get();
    }
}
