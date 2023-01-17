<?php

namespace LaravelOpenAPI\Factory;

use Illuminate\Http\Response;
use LaravelOpenAPI\Request;
use League\OpenAPIValidation\PSR7\Exception\NoResponseCode;
use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\HttpFoundation\Response as BaseResponse;
use Illuminate\Contracts\Support\Responsable;
use Throwable;

class AbortFactory
{
    /**
     * @param  \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable|int  $code
     * @param  mixed[]  $headers
     */
    public function __construct(
        protected mixed $code,
        protected string $message = '',
        protected array $headers = [],
    ) {
    }

    /**
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function forRequest(Request $request): void
    {
        $response = match (true) {
            $this->code instanceof BaseResponse => $this->code,
            $this->code instanceof Responsable => $this->code->toResponse($request),
            default => new Response(
                $this->message,
                is_int($this->code) ? $this->code : Response::HTTP_NOT_IMPLEMENTED,
                $this->headers
            ),
        };

        try {
            ValidatorFactory::forRequest($request)->getResponseValidator()->validate(
                OperationAddressFactory::forRequest($request),
                app(PsrHttpFactory::class)->createResponse($response),
            );

            self::abort();
        } catch (ValidationFailed|NoResponseCode $e) {
            self::forException($e, Response::HTTP_NOT_IMPLEMENTED);
        }
    }

    /**
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function abort(): void
    {
        \abort($this->code, $this->message, $this->headers);
    }

    /**
     * @param mixed[] $headers
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public static function forException(Throwable $e, int $code, array $headers = []): void
    {
        LogFactory::make()->error('[OpenAPI] ' . $e::class . ': ' . $e->getMessage(), ['exception' => $e]);
        \abort($code, $e->getMessage(), $headers);
    }
}
