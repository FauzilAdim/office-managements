<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Division extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    /**
     * Users belonging to this division.
     *
     * @return HasMany<User, $this>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Tasks assigned to users in this division.
     *
     * @return HasManyThrough<Task, User, $this>
     */
    public function tasks(): HasManyThrough
    {
        return $this->hasManyThrough(Task::class, User::class, 'division_id', 'assigned_to');
    }
}
