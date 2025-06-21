<?php

namespace App\Services\Logging;

use App\Services\Logging\Contracts\ThirdPartyLoggable;
use Illuminate\Support\Facades\Log;

class ThirdPartyLogger
{
    public static function log(string $level, string $message, ThirdPartyLoggable $event): void
    {
        $context = ThirdPartyLogContext::from($event);

        Log::channel('thirdparty')->log($level, "[ThirdParty] {$message}", $context);
    }

    public static function warning(string $message, ThirdPartyLoggable $event): void
    {
        self::log('warning', $message, $event);
    }

    public static function error(string $message, ThirdPartyLoggable $event): void
    {
        self::log('error', $message, $event);
    }

    public static function info(string $message, ThirdPartyLoggable $event): void
    {
        self::log('info', $message, $event);
    }
}
