<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\ProjectCategory;
use App\Enums\ProjectStatus;
use App\Enums\RoleEnum;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects accessible to current user.
     */
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = Auth::user();

        $query = Project::query()
            ->with(['members.division', 'creator', 'tasks', 'milestones'])
            ->withCount(['tasks', 'milestones']);

        // RBAC: Direktur & Manager see all projects.
        // Team Lead & Staff only see projects where they are assigned as members.
        if (! $user->hasFullAccess()) {
            $query->whereHas('members', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
        }

        // Filter by category
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by search
        if ($request->filled('search')) {
            $search = strtolower(trim((string) $request->search));
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(code) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(description) LIKE ?', ["%{$search}%"]);
            });
        }

        $projects = $query->latest()->get();

        // Calculate summary statistics
        $allAccessible = ! $user->hasFullAccess()
            ? Project::whereHas('members', fn ($q) => $q->where('users.id', $user->id))->get()
            : Project::all();

        $stats = [
            'total_projects' => $allAccessible->count(),
            'in_progress' => $allAccessible->where('status', ProjectStatus::InProgress)->count(),
            'in_review' => $allAccessible->where('status', ProjectStatus::Review)->count(),
            'planning' => $allAccessible->where('status', ProjectStatus::Planning)->count(),
            'completed' => $allAccessible->where('status', ProjectStatus::Completed)->count(),
        ];

        // List of all active employees for assignment modals (if Director/Manager)
        $employees = $user->hasFullAccess()
            ? User::with('division')->where('is_active', true)->orderBy('name')->get()
            : $user->subordinates()->with('division')->where('is_active', true)->orderBy('name')->get();

        return view('projects.index', [
            'projects' => $projects,
            'stats' => $stats,
            'employees' => $employees,
            'canCreate' => $user->can('create', Project::class),
        ]);
    }

    /**
     * Show form for creating a new project.
     */
    public function create(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $this->authorize('create', Project::class);

        // Get Team Leads and Managers available to be assigned as Project Lead
        $teamLeads = User::role([RoleEnum::TeamLead->value, RoleEnum::Manager->value])
            ->where('is_active', true)
            ->with('division')
            ->orderBy('name')
            ->get();

        return view('projects.create', [
            'categories' => ProjectCategory::cases(),
            'statuses' => ProjectStatus::cases(),
            'teamLeads' => $teamLeads,
        ]);
    }

    /**
     * Store a newly created project.
     */
    public function store(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $this->authorize('create', Project::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50', 'unique:projects,code'],
            'description' => ['nullable', 'string'],
            'category' => ['required', 'string'],
            'status' => ['required', 'string'],
            'priority' => ['required', 'string'],
            'deadline' => ['nullable', 'date'],
            'budget' => ['nullable', 'string', 'max:100'],
            'tech_stack' => ['nullable', 'string'],
            'team_lead_id' => ['nullable', 'exists:users,id'],
            'team_lead_role' => ['nullable', 'string', 'max:100'],
        ]);

        $code = ! empty($validated['code'])
            ? strtoupper(trim($validated['code']))
            : 'PRJ-'.strtoupper(substr($validated['category'], 0, 4)).'-'.strtoupper(Str::random(4));

        $techStack = [];
        if (! empty($validated['tech_stack'])) {
            $techStack = array_values(array_filter(array_map('trim', explode(',', $validated['tech_stack']))));
        }

        $project = Project::create([
            'name' => $validated['name'],
            'code' => $code,
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'],
            'status' => $validated['status'],
            'priority' => $validated['priority'],
            'progress' => 0,
            'deadline' => $validated['deadline'] ?? null,
            'budget' => $validated['budget'] ?? null,
            'tech_stack' => $techStack,
            'created_by' => $user->id,
        ]);

        // Auto assign creator as member
        $project->members()->attach($user->id, [
            'role' => $user->roleDisplayName(),
            'assigned_by' => $user->id,
        ]);

        // If a Team Lead was assigned during creation
        if (! empty($validated['team_lead_id']) && (int) $validated['team_lead_id'] !== $user->id) {
            $project->members()->attach((int) $validated['team_lead_id'], [
                'role' => $validated['team_lead_role'] ?: 'Team Lead',
                'assigned_by' => $user->id,
            ]);
        }

        return redirect()->route('projects.show', $project)
            ->with('success', "Project {$project->name} berhasil dibuat!");
    }

    /**
     * Display the specified project with Milestones, Tasks, and Members.
     */
    public function show(Project $project): View
    {
        /** @var User $user */
        $user = Auth::user();
        $this->authorize('view', $project);

        $project->load([
            'creator',
            'members.division',
            'milestones.tasks' => function ($q) {
                // If staff, only show their assigned tasks, or show all with indicators
                $q->with(['assignee', 'creator'])->orderBy('deadline');
            },
            'tasks.assignee',
            'tasks.creator',
            'tasks.milestone',
        ]);

        // Check if user has management permissions on this project
        $canManageMilestones = $user->can('manageMilestones', $project);
        $canAssignMember = $user->can('assignMember', $project);

        // Get members of this project that can be assigned tasks
        $assignableUsers = $project->members;

        // If project has no members yet, fallback to team leads / subordinates
        if ($assignableUsers->isEmpty()) {
            $assignableUsers = $user->hasFullAccess()
                ? User::where('is_active', true)->get()
                : $user->subordinates()->where('is_active', true)->get();
        }

        // Available employees to add to this project (not yet members)
        $existingMemberIds = $project->members->pluck('id')->toArray();
        $availableToAdd = User::whereNotIn('id', $existingMemberIds)
            ->where('is_active', true)
            ->with('division')
            ->orderBy('name')
            ->get();

        return view('projects.show', [
            'project' => $project,
            'canManageMilestones' => $canManageMilestones,
            'canAssignMember' => $canAssignMember,
            'assignableUsers' => $assignableUsers,
            'availableToAdd' => $availableToAdd,
            'priorities' => TaskPriority::cases(),
            'statuses' => TaskStatus::cases(),
        ]);
    }

    /**
     * Assign a user as a member to the project.
     */
    public function assignMember(Request $request, Project $project): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $this->authorize('assignMember', $project);

        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'role' => ['required', 'string', 'max:100'],
        ]);

        $targetUser = User::findOrFail($validated['user_id']);

        $project->members()->syncWithoutDetaching([
            $targetUser->id => [
                'role' => $validated['role'],
                'assigned_by' => $user->id,
            ],
        ]);

        return redirect()->route('projects.show', $project)
            ->with('success', "{$targetUser->name} berhasil ditugaskan ke project sebagai {$validated['role']}.");
    }

    /**
     * Remove an assigned member from the project.
     */
    public function removeMember(Project $project, User $member): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $this->authorize('assignMember', $project);

        $project->members()->detach($member->id);

        return redirect()->route('projects.show', $project)
            ->with('success', "{$member->name} telah dikeluarkan dari project.");
    }
}
