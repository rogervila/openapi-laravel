<?php

namespace LaravelOpenAPI;

use LaravelOpenAPI\Factory\AbortFactory;
use LaravelOpenAPI\Factory\LogFactory;
use LaravelOpenAPI\Factory\ResponseFactory;
use Psr\Log\LoggerInterface;

class OpenAPI
{
    /**
     * @param  \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\View\View|\Illuminate\Http\Resources\Json\JsonResource|string|array<mixed>|null  $content
     * @param  mixed[]  $headers
     */
    public static function response(mixed $content = '', int $status = 200, array $headers = []): ResponseFactory
    {
        return new ResponseFactory($content, $status, $headers);
    }

    /**
     * @param  \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable|int  $code
     * @param  mixed[]  $headers
     */
    public static function abort(mixed $code, string $message = '', array $headers = []): AbortFactory
    {
        return new AbortFactory($code, $message, $headers);
    }

    public static function log(): LoggerInterface
    {
        return LogFactory::make();
    }
}
