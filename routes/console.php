<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('sessions:mark-missed')->everyThirtyMinutes();
