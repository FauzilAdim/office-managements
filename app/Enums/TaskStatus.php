<?php

declare(strict_types=1);

namespace App\Enums;

enum TaskStatus: string
{
    case Pending = 'pending';
    case InProgress = 'in_progress';
    case Review = 'review';
    case Completed = 'completed';

    /**
     * Get human readable label in Indonesian.
     */
    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Belum Dikerjakan',
            self::InProgress => 'Sedang Dikerjakan',
            self::Review => 'Review',
            self::Completed => 'Selesai',
        };
    }

    /**
     * Get badge CSS classes for Tailwind styling (Framer SaaS Soft Palette).
     */
    public function badgeClasses(): string
    {
        return match ($this) {
            self::Pending => 'bg-[#F4F4F5] text-[#71717A] border border-[#E4E4E7] dark:bg-[#18181B] dark:text-[#A1A1AA] dark:border-[#27272A]',
            self::InProgress => 'bg-[#EFF6FF] text-[#2563EB] border border-[#DBEAFE] dark:bg-blue-950/40 dark:text-blue-400 dark:border-blue-900/50',
            self::Review => 'bg-[#FEF3C7] text-[#B45309] border border-[#FDE68A] dark:bg-amber-950/40 dark:text-amber-400 dark:border-amber-900/50',
            self::Completed => 'bg-[#ECFDF5] text-[#059669] border border-[#A7F3D0] dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-900/50',
        };
    }

    /**
     * Get dot color CSS class.
     */
    public function dotColor(): string
    {
        return match ($this) {
            self::Pending => 'bg-zinc-400',
            self::InProgress => 'bg-blue-500 animate-pulse',
            self::Review => 'bg-amber-500',
            self::Completed => 'bg-emerald-500',
        };
    }
}
