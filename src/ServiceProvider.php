<?php

namespace LaravelOpenAPI;

use Illuminate\Foundation\Console\AboutCommand;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /** {@inheritDoc} */
    public function register(): void
    {
        $this->app->singleton(
            ValidatorBuilder::class,
            fn () => new ValidatorBuilder()
        );

        $this->app->singleton(
            PsrHttpFactory::class,
            fn () => new PsrHttpFactory($psr17Factory = new Psr17Factory(), $psr17Factory, $psr17Factory, $psr17Factory)
        );

        AboutCommand::add('rogervila/openapi-laravel', [
            'Log channel' => fn () => config('openapi.log.channel'),
            'Log context' => fn () => config('openapi.log.context'),
        ]);
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \LaravelOpenAPI\Command\MakeRequestCommand::class,
                \LaravelOpenAPI\Command\MakeSpecificationCommand::class,
            ]);
        }

        $this->publishes([
            __DIR__.'/../config/openapi.php' => config_path('openapi.php'),
        ], 'openapi-config');
    }
}
