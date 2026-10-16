<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskLog extends Model
{
    use HasFactory;

    /**
     * Disable updated_at since logs are immutable audit records.
     */
    public $timestamps = false;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'task_id',
        'user_id',
        'from_status',
        'to_status',
        'from_progress',
        'to_progress',
        'note',
        'created_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'from_progress' => 'integer',
            'to_progress' => 'integer',
        ];
    }

    /**
     * The task this log belongs to.
     *
     * @return BelongsTo<Task, $this>
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * The user who performed this action.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
