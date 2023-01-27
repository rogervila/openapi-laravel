<?php

namespace App\Http\Requests\OpenAPI;

use App\Specifications\PetsSpecification;
use LaravelOpenAPI\Request;

class PetRequest extends Request
{
    /** {@inheritDoc} */
    public function getSpecification(): string
    {
        return PetsSpecification::class;
    }
}
