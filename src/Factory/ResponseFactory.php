<?php

namespace LaravelOpenAPI\Factory;

use ArrayObject;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use JsonSerializable;
use LaravelOpenAPI\Request;
use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\HttpFoundation\Response as BaseResponse;

class ResponseFactory
{
    protected BaseResponse $response;

    /**
     * @param  \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\View\View|\Illuminate\Http\Resources\Json\JsonResource|string|array<mixed>|null  $content
     * @param  mixed[]  $headers
     */
    public function __construct(mixed $content = '', int $status = 200, array $headers = [])
    {
        if ($content instanceof BaseResponse) {
            $this->response = $content;

            return;
        }

        $this->response = $this->shouldBeJson($content)
            ? new JsonResponse($content, $status, $headers)
            : new Response($content, $status, $headers);
    }

    protected function shouldBeJson(mixed $content): bool
    {
        return $content instanceof Arrayable ||
               $content instanceof Jsonable ||
               $content instanceof ArrayObject ||
               $content instanceof JsonSerializable ||
               is_array($content);
    }

    /**
     * Create a new HTTP response.
     *
     * @param  mixed[]  $headers
     *
     * @throws \InvalidArgumentException
     */
    public static function make(mixed $content = '', int $status = 200, array $headers = []): self
    {
        return new self(new Response($content, $status, $headers));
    }

    /**
     * @param  mixed[]  $headers
     */
    public static function noContent(int $status = 204, array $headers = []): self
    {
        return new self(new Response('', $status, $headers));
    }

    /**
     * Create a new JSON response.
     *
     * @param  mixed[]  $headers
     */
    public static function json(mixed $data = null, int $status = 200, array $headers = [], int $options = 0, bool $json = false): self
    {
        return new self(new JsonResponse($data, $status, $headers, $options, $json));
    }

    /**
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function forRequest(Request $request): BaseResponse
    {
        try {
            ValidatorFactory::forRequest($request)->getResponseValidator()->validate(
                OperationAddressFactory::forRequest($request),
                app(PsrHttpFactory::class)->createResponse($this->response),
            );

            return $this->response;
        } catch (ValidationFailed $e) {
            AbortFactory::forException($e, Response::HTTP_NOT_IMPLEMENTED);
            return $this->response;
        }
    }
}
