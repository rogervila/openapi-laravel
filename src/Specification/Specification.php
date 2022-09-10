<?php

namespace LaravelOpenAPI\Specification;

interface Specification
{
    /**
     * Must return the specification content, either JSON when it
     * implements \LaravelOpenAPI\Specification\JsonSpecification
     * or YAML for \LaravelOpenAPI\Specification\YamlSpecification
     */
    public function __invoke(): string;
}
