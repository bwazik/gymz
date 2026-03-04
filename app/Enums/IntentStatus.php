<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum IntentStatus: int implements HasLabel, HasColor
{
    case Active = 0;
    case Matched = 1;
    case Expired = 2;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Active => __('نشط'),
            self::Matched => __('تم الإتفاق'),
            self::Expired => __('منتهي'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Active => 'success',
            self::Matched => 'info',
            self::Expired => 'secondary',
        };
    }
}
