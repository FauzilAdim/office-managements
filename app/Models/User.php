<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\RoleEnum;
use App\Enums\TaskStatus;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'phone',
        'telegram_username',
        'position',
        'division_id',
        'supervisor_id',
        'join_date',
        'address',
        'is_active',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'join_date' => 'date',
            'is_active' => 'boolean',
            'password' => 'hashed',
        ];
    }

    /**
     * Direct supervisor / line manager.
     *
     * @return BelongsTo<User, $this>
     */
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(self::class, 'supervisor_id');
    }

    /**
     * Direct subordinates report to this user.
     *
     * @return HasMany<User, $this>
     */
    public function subordinates(): HasMany
    {
        return $this->hasMany(self::class, 'supervisor_id');
    }

    /**
     * The division or department.
     *
     * @return BelongsTo<Division, $this>
     */
    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * Tasks assigned to this user.
     *
     * @return HasMany<Task, $this>
     */
    public function assignedTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    /**
     * Tasks created / assigned by this user to others.
     *
     * @return HasMany<Task, $this>
     */
    public function createdTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_by');
    }

    /**
     * Audit logs created by this user.
     *
     * @return HasMany<TaskLog, $this>
     */
    public function taskLogs(): HasMany
    {
        return $this->hasMany(TaskLog::class);
    }

    /**
     * Get the task currently being worked on (in progress).
     */
    public function currentActiveTask(): ?Task
    {
        return $this->assignedTasks()
            ->where('status', TaskStatus::InProgress->value)
            ->with('creator')
            ->latest('started_at')
            ->first();
    }

    /**
     * Count active tasks visible to this user for the sidebar notification badge.
     */
    public function activeTasksCount(): int
    {
        $query = Task::query()->whereIn('status', [
            TaskStatus::Pending->value,
            TaskStatus::InProgress->value,
            TaskStatus::Review->value,
        ]);

        if ($this->isDirector()) {
            return $query->count();
        }

        if ($this->isVP()) {
            return $query->whereHas('assignee', fn ($q) => $q->where('division_id', $this->division_id))->count();
        }

        if ($this->isManager() || $this->isTeamLead()) {
            $visibleUserIds = array_merge([$this->id], $this->allSubordinateIds());

            return $query->whereIn('assigned_to', $visibleUserIds)->count();
        }

        return $query->where('assigned_to', $this->id)->count();
    }

    /**
     * Recursively fetch all subordinate IDs under this user.
     *
     * @return array<int>
     */
    public function allSubordinateIds(): array
    {
        $ids = [];
        $directSubordinates = $this->subordinates()->get();

        foreach ($directSubordinates as $subordinate) {
            $ids[] = $subordinate->id;
            $ids = array_merge($ids, $subordinate->allSubordinateIds());
        }

        return array_unique($ids);
    }

    /**
     * Get display role name.
     */
    public function roleDisplayName(): string
    {
        $role = $this->roles->first()?->name;

        return $role ?? 'Employee';
    }

    /**
     * Check if user is Direktur.
     */
    public function isDirector(): bool
    {
        return $this->hasRole(RoleEnum::Director->value);
    }

    /**
     * Check if user is VP.
     */
    public function isVP(): bool
    {
        return $this->hasRole(RoleEnum::VicePresident->value);
    }

    /**
     * Check if user is Manager.
     */
    public function isManager(): bool
    {
        return $this->hasRole(RoleEnum::Manager->value);
    }

    /**
     * Check if user is Team Lead.
     */
    public function isTeamLead(): bool
    {
        return $this->hasRole(RoleEnum::TeamLead->value);
    }

    /**
     * Check if user is Staff.
     */
    public function isStaff(): bool
    {
        return $this->hasRole(RoleEnum::Staff->value);
    }

    /**
     * Get avatar URL (fallback to UI Avatars).
     */
    public function avatarUrl(): string
    {
        if ($this->avatar && Storage::disk('public')->exists($this->avatar)) {
            return Storage::disk('public')->url($this->avatar);
        }

        $encodedName = urlencode($this->name);

        return "https://ui-avatars.com/api/?name={$encodedName}&background=0284c7&color=ffffff&bold=true";
    }

    /**
     * Get direct Telegram chat link.
     */
    public function telegramUrl(): ?string
    {
        if (! $this->telegram_username) {
            return null;
        }

        $clean = ltrim($this->telegram_username, '@');

        return "https://t.me/{$clean}";
    }
}
