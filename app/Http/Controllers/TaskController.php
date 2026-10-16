<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Requests\UpdateTaskStatusRequest;
use App\Models\Task;
use App\Models\User;
use App\Services\HierarchyService;
use App\Services\TaskService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function __construct(
        private readonly TaskService $taskService,
        private readonly HierarchyService $hierarchyService
    ) {}

    /**
     * Display a listing of tasks visible to current user based on RBAC hierarchy.
     */
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = Auth::user();

        $query = Task::query()->with(['assignee.division', 'creator']);

        if ($user->isDirector()) {
            // Full access: see all tasks
        } elseif ($user->isVP()) {
            // Tasks in VP's division
            $query->whereHas('assignee', fn (Builder $q) => $q->where('division_id', $user->division_id));
        } elseif ($user->isManager() || $user->isTeamLead()) {
            // Tasks assigned to self or subordinates
            $visibleUserIds = array_merge([$user->id], $user->allSubordinateIds());
            $query->whereIn('assigned_to', $visibleUserIds);
        } else {
            // Staff: only own tasks
            $query->where('assigned_to', $user->id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Search by title or employee name
        if ($request->filled('search')) {
            $search = '%'.$request->search.'%';
            $query->where(function (Builder $q) use ($search) {
                $q->where('title', 'like', $search)
                    ->orWhere('description', 'like', $search)
                    ->orWhereHas('assignee', fn (Builder $sub) => $sub->where('name', 'like', $search));
            });
        }

        $allTasks = $query->latest('deadline')->get();

        $pendingTasks = $allTasks->where('status', TaskStatus::Pending);
        $inProgressTasks = $allTasks->where('status', TaskStatus::InProgress);
        $reviewTasks = $allTasks->where('status', TaskStatus::Review);
        $completedTasks = $allTasks->where('status', TaskStatus::Completed);

        // Dynamic Team members involved in these tasks
        $teamMembers = $allTasks->pluck('assignee')->filter()->unique('id');

        // Counts for tabs
        $totalTasksCount = $allTasks->count();
        $targetTasksCount = $allTasks->whereIn('status', [TaskStatus::Pending, TaskStatus::InProgress])->count();
        $reviewTasksCount = $reviewTasks->count();
        $favoriteTasksCount = $allTasks->where('priority', TaskPriority::High)->count();

        return view('tasks.index', [
            'tasks' => $allTasks,
            'pendingTasks' => $pendingTasks,
            'inProgressTasks' => $inProgressTasks,
            'reviewTasks' => $reviewTasks,
            'completedTasks' => $completedTasks,
            'teamMembers' => $teamMembers,
            'totalTasksCount' => $totalTasksCount,
            'targetTasksCount' => $targetTasksCount,
            'reviewTasksCount' => $reviewTasksCount,
            'favoriteTasksCount' => $favoriteTasksCount,
            'statuses' => TaskStatus::cases(),
            'priorities' => TaskPriority::cases(),
            'canCreateTask' => $user->can('create', Task::class),
        ]);
    }

    /**
     * Show form for creating a new task.
     */
    public function create(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $this->authorize('create', Task::class);

        $assignableUsers = $this->hierarchyService->getAssignableUsers($user);

        return view('tasks.create', [
            'assignableUsers' => $assignableUsers,
            'priorities' => TaskPriority::cases(),
            'statuses' => TaskStatus::cases(),
        ]);
    }

    /**
     * Store a newly created task.
     */
    public function store(StoreTaskRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $task = $this->taskService->createTask($request->validated(), $user);

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Tugas berhasil dibuat dan ditugaskan kepada '.$task->assignee?->name.'.');
    }

    /**
     * Display task details along with audit history.
     */
    public function show(Task $task): View
    {
        $this->authorize('view', $task);

        $task->load([
            'assignee.division',
            'creator.division',
            'logs.user',
        ]);

        return view('tasks.show', [
            'task' => $task,
            'statuses' => TaskStatus::cases(),
            'canUpdate' => Auth::user()->can('update', $task),
            'canUpdateStatus' => Auth::user()->can('updateStatus', $task),
            'canDelete' => Auth::user()->can('delete', $task),
        ]);
    }

    /**
     * Show edit form for task.
     */
    public function edit(Task $task): View
    {
        $this->authorize('update', $task);

        /** @var User $user */
        $user = Auth::user();
        $assignableUsers = $this->hierarchyService->getAssignableUsers($user);

        return view('tasks.edit', [
            'task' => $task,
            'assignableUsers' => $assignableUsers,
            'priorities' => TaskPriority::cases(),
        ]);
    }

    /**
     * Update task details.
     */
    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $this->taskService->updateTask($task, $request->validated(), $user);

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Detail tugas berhasil diperbarui.');
    }

    /**
     * Update task status & progress with audit logging.
     */
    public function updateStatus(UpdateTaskStatusRequest $request, Task $task): RedirectResponse|JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $newStatus = TaskStatus::from($request->status);
        $progress = $request->filled('progress') ? (int) $request->progress : null;
        $note = $request->note;

        $this->taskService->updateStatus($task, $newStatus, $progress, $note, $user);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'status' => $newStatus->value,
                'label' => $newStatus->label(),
                'message' => "Status tugas berhasil diperbarui menjadi {$newStatus->label()}.",
            ]);
        }

        return back()->with('success', "Status tugas berhasil diperbarui menjadi {$newStatus->label()}.");
    }

    /**
     * Delete a task.
     */
    public function destroy(Task $task): RedirectResponse
    {
        $this->authorize('delete', $task);

        $this->taskService->deleteTask($task);

        return redirect()->route('tasks.index')
            ->with('success', 'Tugas berhasil dihapus.');
    }
}
