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

        $uri = $route instanceof \Illuminate\Routing\Route ? $route->uri : (string) $route;

        return new OperationAddress(
            Str::start($uri, $prefix),
            Str::lower($request->method())
        );
    }
}
