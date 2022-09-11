<?php

namespace LaravelOpenAPI;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use LaravelOpenAPI\Factory\AbortFactory;
use LaravelOpenAPI\Factory\OperationAddressFactory;
use LaravelOpenAPI\Factory\ValidatorFactory;
use League\OpenAPIValidation\PSR7\Exception\MultipleOperationsMismatchForRequest;
use League\OpenAPIValidation\PSR7\Exception\NoOperation;
use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;

/**
 * OpenAPI Requests extend from Illuminate\Foundation\Http\FormRequest,
 * Which allows you to use authorize() and rules() methods.
 *
 * @see https://laravel.com/docs/validation#form-request-validation
 */
abstract class Request extends FormRequest
{
    public function authorize(): bool
    {
        // TODO: check specification to determine authorized endpoints
        return true;
    }

    /**
     * @return array<string,mixed>
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Defines which Specification class must be used for the current request
     */
    abstract public function getSpecification(): string;

    /**
     * {@inheritDoc}
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function validateResolved(): void
    {
        try {
            ValidatorFactory::forRequest($this)->getRoutedRequestValidator()->validate(
                OperationAddressFactory::forRequest($this),
                app(PsrHttpFactory::class)->createRequest($this)
            );
        } catch (NoOperation|MultipleOperationsMismatchForRequest|ValidationFailed $e) {
            AbortFactory::forException($e, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
