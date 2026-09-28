<?php

namespace App\Support;

use Carbon\CarbonInterface;

class AppDateTime
{
    public static function iso(?CarbonInterface $dt): ?string
    {
        if (! $dt) {
            return null;
        }

        return $dt->timezone(config('app.timezone', 'Asia/Jakarta'))->format('Y-m-d\TH:i:sP');
    }

    public static function display(?CarbonInterface $dt, string $format = 'd/m/Y H:i'): ?string
    {
        if (! $dt) {
            return null;
        }

        return $dt->timezone(config('app.timezone', 'Asia/Jakarta'))->format($format);
    }
}
