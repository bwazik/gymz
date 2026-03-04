<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum TransactionType: int implements HasLabel, HasColor
{
    case Earned = 0;
    case Spent = 1;
    case Deducted = 2;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Earned => __('ربح'),
            self::Spent => __('دفع'),
            self::Deducted => __('خصم'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Earned => 'success',
            self::Spent => 'warning',
            self::Deducted => 'danger',
        };
    }
}
