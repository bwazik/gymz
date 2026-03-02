<?php

namespace App\Enums;

enum SessionStatus: int
{
    case Scheduled = 0;
    case InProgress = 1;
    case Completed = 2;
    case Disputed = 3;
}
