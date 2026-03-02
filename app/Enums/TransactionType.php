<?php

namespace App\Enums;

enum TransactionType: int
{
    case Earned = 0;
    case Spent = 1;
    case Deducted = 2;
}
