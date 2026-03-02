<?php

namespace App\Enums;

enum RequestStatus: int
{
    case Pending = 0;
    case Accepted = 1;
    case Rejected = 2;
}
