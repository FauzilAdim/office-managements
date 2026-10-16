<?php

declare(strict_types=1);

namespace App\Enums;

enum RoleEnum: string
{
    case Director = 'Direktur';
    case VicePresident = 'VP';
    case Manager = 'Manager';
    case TeamLead = 'Team Lead';
    case Staff = 'Staff';

    /**
     * Get label for display.
     */
    public function label(): string
    {
        return match ($this) {
            self::Director => 'Direktur',
            self::VicePresident => 'Vice President (VP)',
            self::Manager => 'Manager',
            self::TeamLead => 'Team Lead',
            self::Staff => 'Staff',
        };
    }

    /**
     * Get hierarchical level weight (higher means higher authority).
     */
    public function level(): int
    {
        return match ($this) {
            self::Director => 5,
            self::VicePresident => 4,
            self::Manager => 3,
            self::TeamLead => 2,
            self::Staff => 1,
        };
    }
}
