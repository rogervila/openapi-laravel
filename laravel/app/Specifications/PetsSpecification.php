<?php

namespace App\Specifications;

use LaravelOpenAPI\Specification\YamlSpecification;

class PetsSpecification implements YamlSpecification
{
    /** {@inheritDoc} */
    public function __invoke(): string
    {
        return file_get_contents(storage_path('openapi/pets.yml'));
    }
}
