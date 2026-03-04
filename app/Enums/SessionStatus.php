<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum SessionStatus: int implements HasLabel, HasColor
{
    case Scheduled = 0;
    case InProgress = 1;
    case Completed = 2;
    case Disputed = 3;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Scheduled => __('مجدول'),
            self::InProgress => __('قيد التنفيذ'),
            self::Completed => __('مكتمل'),
            self::Disputed => __('متنازع عليه'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Scheduled => 'info',
            self::InProgress => 'warning',
            self::Completed => 'success',
            self::Disputed => 'danger',
        };
    }
}
