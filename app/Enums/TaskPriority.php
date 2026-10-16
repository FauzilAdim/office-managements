<?php

declare(strict_types=1);

namespace App\Enums;

enum TaskPriority: string
{
    case Low = 'low';
    case Medium = 'medium';
    case High = 'high';
    case Urgent = 'urgent';

    /**
     * Get human readable label in Indonesian.
     */
    public function label(): string
    {
        return match ($this) {
            self::Low => 'Rendah',
            self::Medium => 'Sedang',
            self::High => 'Tinggi',
            self::Urgent => 'Mendesak',
        };
    }

    /**
     * Get badge CSS classes for Tailwind styling (Framer SaaS Soft Palette).
     */
    public function badgeClasses(): string
    {
        return match ($this) {
            self::Low => 'bg-[#F4F4F5] text-[#71717A] border border-[#E4E4E7] dark:bg-[#18181B] dark:text-[#A1A1AA] dark:border-[#27272A]',
            self::Medium => 'bg-[#EEF2FF] text-[#4F46E5] border border-[#E0E7FF] dark:bg-indigo-950/40 dark:text-indigo-400 dark:border-indigo-900/50',
            self::High => 'bg-[#FFF7ED] text-[#C2410C] border border-[#FFEDD5] dark:bg-orange-950/40 dark:text-orange-400 dark:border-orange-900/50',
            self::Urgent => 'bg-[#FEF2F2] text-[#DC2626] border border-[#FECACA] dark:bg-red-950/40 dark:text-red-400 dark:border-red-900/50',
        };
    }
}
