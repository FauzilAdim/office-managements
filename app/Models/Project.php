<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ProjectCategory;
use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'category',
        'status',
        'priority',
        'progress',
        'deadline',
        'budget',
        'tech_stack',
        'created_by',
    ];

    /**
     * @return array<string, string>
     */
    protected $casts = [
        'status' => ProjectStatus::class,
        'category' => ProjectCategory::class,
        'deadline' => 'date',
        'progress' => 'integer',
        'tech_stack' => 'array',
    ];

    /**
     * @var list<string>
     */
    protected $appends = [
        'status_label',
    ];

    public function getStatusLabelAttribute(): string
    {
        return $this->status ? $this->status->label() : 'Planning';
    }

    /**
     * The creator of the project (Director / Manager).
     *
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Project members assigned to this project.
     *
     * @return BelongsToMany<User, $this>
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_members')
            ->withPivot(['role', 'assigned_by'])
            ->withTimestamps();
    }

    /**
     * Milestones breakdown for this project.
     *
     * @return HasMany<Milestone, $this>
     */
    public function milestones(): HasMany
    {
        return $this->hasMany(Milestone::class)->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Tasks associated with this project.
     *
     * @return HasMany<Task, $this>
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Check if a specific user is an assigned member of this project.
     */
    public function hasMember(User|int $user): bool
    {
        $userId = $user instanceof User ? $user->id : $user;

        return $this->members()->where('users.id', $userId)->exists();
    }

    /**
     * Calculate and update progress based on tasks.
     */
    public function recalculateProgress(): int
    {
        $total = $this->tasks()->count();
        if ($total === 0) {
            return $this->progress;
        }

        $completed = $this->tasks()->where('status', 'completed')->count();
        $calc = (int) round(($completed / $total) * 100);
        $this->update(['progress' => $calc]);

        return $calc;
    }
}
