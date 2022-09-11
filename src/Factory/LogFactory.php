<?php

namespace LaravelOpenAPI\Factory;

use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;

class LogFactory
{
    public static function make(): LoggerInterface
    {
        Log::withContext(config('openapi.log.context', []));

        return Log::channel(config('openapi.log.channel', 'null'));
    }
}
