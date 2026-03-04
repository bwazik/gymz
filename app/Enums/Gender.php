<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum Gender: int implements HasLabel, HasColor
{
    case Male = 0;
    case Female = 1;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Male => __('ذكر'),
            self::Female => __('أنثى'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Male => 'info',
            self::Female => 'danger',
        };
    }
}
