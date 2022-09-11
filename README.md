<p align="center"><img height="200" src="https://cdn.worldvectorlogo.com/logos/openapi-1.svg" alt="OpenAPI" /><img height="200" src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Laravel.svg/985px-Lravel.svg.png" alt="Laravel" /></p>

[![Build Status](https://github.com/rogervila/openapi-laravel/workflows/build/badge.svg)](https://github.com/rogervila/array-diff-multidimensional/actions)
[![StyleCI](https://github.styleci.io/repos/534774994/shield?branch=main)](https://github.styleci.io/repos/534774994)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=rogervila_openapi-laravel&metric=alert_status)](https://sonarcloud.io/dashboard?id=rogervila_openapi-laravel)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=rogervila_openapi-laravel&metric=coverage)](https://sonarcloud.io/dashboard?id=rogervila_openapi-laravel)

[![Latest Stable Version](https://poser.pugx.org/rogervila/openapi-laravel/v/stable)](https://packagist.org/packages/rogervila/openapi-laravel)
[![Total Downloads](https://poser.pugx.org/rogervila/openapi-laravel/downloads)](https://packagist.org/packages/rogervila/openapi-laravel)
[![License](https://poser.pugx.org/rogervila/openapi-laravel/license)](https://packagist.org/packages/rogervila/openapi-laravel)

# OpenAPI Laravel

## About

Validate HTTP Requests and Responses with OpenAPI Specs.

## Install

Require the package with composer.

```sh
composer require rogervila/openapi-laravel
```

Laravel will autodiscover the service provider located in `\LaravelOpenAPI\ServiceProvider`.

## Setup

The package handles HTTP requests via Specification and Request classes.

### OpenAPI Specifications

First, create a Specification class.

```sh
# The namespace is optional, you can place specification classes anywhere.
php artisan openapi:make-specification Specifications/PetsSpecification
```

```php
<?php

namespace App\Specifications;

use LaravelOpenAPI\Specification\YamlSpecification;
use LaravelOpenAPI\Specification\JsonSpecification;

// The Specification class must implement YamlSpecification or JsonSpecification interface.
class PetsSpecification implements YamlSpecification
{
    public function __invoke(): string
    {
        // As long as it returns a yaml or json string, you can resolve it as needed.
        return file_get_contents(storage_path('openapi/pets.yml'));
        
        // Another example (JSON in this case)
        // return \Cache::rememberForever(self::class, fn () => \Http::get('https://petstore3.swagger.io/api/v3/openapi.json')->body());
    }
}
```

### OpenAPI Requests

Once a Specification is set, You can create OpenAPI Request and **define which specification it should use**.

The package will handle the request validation based on the specification.

- If it **does not match**, it will return a **"`422` validation error"**. 
- If the **specification is missing**, it will return a **"`501` not implemented"** error.

```sh
# Requests are placed on \App\Http\Requests\OpenAPI namespace
php artisan openapi:make-request PetRequest
```

OpenAPI Requests extend from `\Illuminate\Foundation\Http\FormRequest`, so you might implement additional rules and authorization as defined on [laravel docs](https://laravel.com/docs/validation#form-request-validation)

```php
<?php

namespace App\Http\Requests\OpenAPI;

use LaravelOpenAPI\Request;

class PetRequest extends Request
{
    /** {@inheritDoc} */
    public function getSpecification(): string
    {
        return \App\Specifications\PetsSpecification::class;
    }
}
```

## Usage

The package includes the `\LaravelOpenAPI\OpenAPI` class as an accessor for all the package features.

Check this repository's `laravel` folder to see the package usage on a real laravel project.

### Example

This example defines a FindPetByIdController controller which uses the package to handle the request and the response.

```php
<?php

namespace App\Http\Controllers\Pet;

use App\Http\Controllers\Controller;
use App\Http\Requests\OpenAPI\PetRequest;
use App\Http\Resources\PetResource;
use App\Models\Pet;
use Illuminate\Http\JsonResponse;
use LaravelOpenAPI\OpenAPI;

// GET /api/pet/{petId}
class FindPetByIdController extends Controller
{
    public function __invoke(PetRequest $request, int $petId): JsonResponse
    {
        if (is_null($pet = Pet::find($petId))) {
            OpenAPI::abort(JsonResponse::HTTP_NOT_FOUND)->forRequest($request);
        }

        return OpenAPI::response(new PetResource($pet))->forRequest($request);
    }
}
```

## Author

Created by [Roger Vilà](https://rogervila.es)

## License

OpenAPI Laravel is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
