<?php

namespace App\Facades;

use App\Services\Logging\Contracts\ThirdPartyLoggable;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void log(string $level, string $message, ThirdPartyLoggable $event)
 * @method static void warning(string $message, ThirdPartyLoggable $event)
 * @method static void error(string $message, ThirdPartyLoggable $event)
 * @method static void info(string $message, ThirdPartyLoggable $event)
 *
 * @see \App\Services\Logging\ThirdPartyLogger
 */
class ThirdPartyLogger extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'thirdparty-logger';
    }
}
