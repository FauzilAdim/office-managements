<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'project_id',
        'milestone_id',
        'title',
        'description',
        'deadline',
        'priority',
        'status',
        'progress',
        'assigned_by',
        'assigned_to',
        'started_at',
        'completed_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'deadline' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'priority' => TaskPriority::class,
            'status' => TaskStatus::class,
            'progress' => 'integer',
        ];
    }

    /**
     * The project this task belongs to.
     *
     * @return BelongsTo<Project, $this>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * The milestone this task belongs to.
     *
     * @return BelongsTo<Milestone, $this>
     */
    public function milestone(): BelongsTo
    {
        return $this->belongsTo(Milestone::class);
    }

    /**
     * The supervisor / user who created and assigned the task.
     *
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * The employee who is assigned this task.
     *
     * @return BelongsTo<User, $this>
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * The audit change logs for this task.
     *
     * @return HasMany<TaskLog, $this>
     */
    public function logs(): HasMany
    {
        return $this->hasMany(TaskLog::class)->orderBy('created_at', 'desc');
    }

    /**
     * Check if task is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->status !== TaskStatus::Completed && $this->deadline->isPast();
    }

    /**
     * Scope query to only overdue tasks.
     *
     * @param  Builder<Task>  $query
     */
    public function scopeOverdue(Builder $query): Builder
    {
        return $query->where('status', '!=', TaskStatus::Completed->value)
            ->where('deadline', '<', now());
    }

    /**
     * Scope query to only active tasks (in_progress or pending or review).
     *
     * @param  Builder<Task>  $query
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', [
            TaskStatus::Pending->value,
            TaskStatus::InProgress->value,
            TaskStatus::Review->value,
        ]);
    }

    /**
     * Scope query to completed tasks.
     *
     * @param  Builder<Task>  $query
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', TaskStatus::Completed->value);
    }
}
