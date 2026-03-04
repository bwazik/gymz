<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum RequestStatus: int implements HasLabel, HasColor
{
    case Pending = 0;
    case Accepted = 1;
    case Rejected = 2;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Pending => __('قيد الانتظار'),
            self::Accepted => __('مقبول'),
            self::Rejected => __('مرفوض'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Accepted => 'success',
            self::Rejected => 'danger',
        };
    }
}
