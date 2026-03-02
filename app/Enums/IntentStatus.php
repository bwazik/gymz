<?php

namespace App\Enums;

enum IntentStatus: int
{
    case Active = 0;
    case Matched = 1;
    case Expired = 2;
}
