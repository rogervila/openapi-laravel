<?php

namespace LaravelOpenAPI\Factory;

use Illuminate\Support\Str;
use LaravelOpenAPI\Request;
use League\OpenAPIValidation\PSR7\OperationAddress;

class OperationAddressFactory
{
    public static function forRequest(Request $request, string $prefix = '/'): OperationAddress
    {
        if (is_null($route = $request->route())) {
            throw new \UnexpectedValueException('$request->route() should not be null');
        }

        $uri = match (true) {
            $route instanceof \Illuminate\Routing\Route => $route->uri,
            is_string($route) => $route,
            default => throw new \RuntimeException('Invalid request route'),
        };

        return new OperationAddress(
            Str::start($uri, $prefix),
            Str::lower($request->method())
        );
    }
}
