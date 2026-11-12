<?php

declare(strict_types=1);

namespace App\Enums;

enum ProjectCategory: string
{
    case Tech = 'tech';
    case Marketing = 'marketing';
    case Operations = 'operations';
    case HR = 'hr';

    public function label(): string
    {
        return match ($this) {
            self::Tech => 'Technology & Engineering',
            self::Marketing => 'Product & Marketing',
            self::Operations => 'Finance & Operations',
            self::HR => 'Human Resources',
        };
    }
}
