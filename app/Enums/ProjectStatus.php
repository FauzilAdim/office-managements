<?php

declare(strict_types=1);

namespace App\Enums;

enum ProjectStatus: string
{
    case Planning = 'planning';
    case InProgress = 'in_progress';
    case Review = 'review';
    case Completed = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::Planning => 'Planning',
            self::InProgress => 'In Progress',
            self::Review => 'Review',
            self::Completed => 'Completed',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Planning => 'amber',
            self::InProgress => 'emerald',
            self::Review => 'blue',
            self::Completed => 'zinc',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Planning => 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20',
            self::InProgress => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20',
            self::Review => 'bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20',
            self::Completed => 'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400 border border-zinc-200 dark:border-zinc-700',
        };
    }

    public function dotColor(): string
    {
        return match ($this) {
            self::Planning => 'bg-amber-500',
            self::InProgress => 'bg-emerald-500 animate-pulse',
            self::Review => 'bg-blue-500',
            self::Completed => 'bg-zinc-400',
        };
    }
}
