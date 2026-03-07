<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum SessionStatus: int implements HasLabel, HasColor
{
    case Scheduled = 0;
    case InProgress = 1;
    case Completed = 2;
    case Missed = 3;
    case Cancelled_By_Host = 4;
    case Cancelled_By_Guest = 5;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Scheduled => __('مجدولة'),
            self::InProgress => __('قيد التنفيذ'),
            self::Completed => __('تمت'),
            self::Missed => __('فائتة'),
            self::Cancelled_By_Host => __('تم الإلغاء من المستضيف'),
            self::Cancelled_By_Guest => __('تم الانسحاب من الضيف'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Scheduled => 'info',
            self::InProgress => 'warning',
            self::Completed => 'success',
            self::Missed => 'danger',
            self::Cancelled_By_Host => 'danger',
            self::Cancelled_By_Guest => 'danger',
        };
    }
}
