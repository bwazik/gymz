<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum UserLevel: int implements HasLabel, HasColor
{
    case Beginner = 0;
    case Mid = 1;
    case Pro = 2;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Beginner => __('قطة'),
            self::Mid => __('أسد'),
            self::Pro => __('فحل'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Beginner => 'info',
            self::Mid => 'success',
            self::Pro => 'warning',
        };
    }
}
