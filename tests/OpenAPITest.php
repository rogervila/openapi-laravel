<?php

namespace Tests\LaravelOpenAPI;

use LaravelOpenAPI\Factory\AbortFactory;
use LaravelOpenAPI\Factory\ResponseFactory;
use LaravelOpenAPI\OpenAPI;

class OpenAPITest extends \PHPUnit\Framework\TestCase
{
    public function test_response_returns_response_factory(): void
    {
        $this->assertInstanceOf(
            ResponseFactory::class,
            OpenAPI::response('foo')
        );
    }

    public function test_abort_returns_abort_factory(): void
    {
        $this->assertInstanceOf(
            AbortFactory::class,
            OpenAPI::abort(404)
        );
    }
}
