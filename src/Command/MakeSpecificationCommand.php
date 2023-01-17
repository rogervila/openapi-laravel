<?php

namespace LaravelOpenAPI\Command;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'openapi:make-specification')]
class MakeSpecificationCommand extends GeneratorCommand
{
    /** {@inheritDoc} */
    protected $signature = 'openapi:make-specification {name}';

    /** {@inheritDoc} */
    protected $description = 'Create a OpenAPI Specification';

    /** {@inheritDoc} */
    public function getStub(): string
    {
        return $this->resolveStubPath('specification.stub');
    }

    protected function resolveStubPath(string $stub): string
    {
        return __DIR__ . '/../../stubs/'.ltrim($stub, '/');
    }

    /**
     * @param string $rootNamespace
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace;
    }
}
