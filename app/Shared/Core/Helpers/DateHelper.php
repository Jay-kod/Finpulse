<?php

namespace App\Shared\Core\Helpers;

use Carbon\Carbon;

class DateHelper
{
    public static function format(string $date, string $format = 'M d, Y H:i A'): string
    {
        return Carbon::parse($date)->format($format);
    }

    public static function humanize(string $date): string
    {
        return Carbon::parse($date)->diffForHumans();
    }
}
