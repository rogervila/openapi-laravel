<?php

namespace LaravelOpenAPI\Command;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'openapi:make-request')]
class MakeRequestCommand extends GeneratorCommand
{
    /** {@inheritDoc} */
    protected $signature = 'openapi:make-request {name}';

    /** {@inheritDoc} */
    protected $description = 'Create a OpenAPI Request';

    /** {@inheritDoc} */
    public function getStub(): string
    {
        return $this->resolveStubPath('request.stub');
    }

    protected function resolveStubPath(string $stub): string
    {
        return __DIR__ . '/../../stubs/' . ltrim($stub, '/');
    }

    /**
     * @param string $rootNamespace
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Http\Requests\OpenAPI';
    }
}
