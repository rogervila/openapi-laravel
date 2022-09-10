<?php

namespace LaravelOpenAPI\Factory;

use LaravelOpenAPI\Request;
use LaravelOpenAPI\Specification\JsonSpecification;
use LaravelOpenAPI\Specification\YamlSpecification;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;

class ValidatorFactory
{
    protected static ?ValidatorBuilder $validator = null;

    protected static function make(): ValidatorBuilder
    {
        if (! self::$validator instanceof ValidatorBuilder) {
            /** @var \Symfony\Component\Cache\Adapter\Psr16Adapter $cache */
            $cache = app('cache.psr6');
            self::$validator = app(ValidatorBuilder::class)->setCache($cache);
        }

        return self::$validator;
    }

    /**
     * @throws \InvalidArgumentException
     */
    public static function forRequest(Request $request): ValidatorBuilder
    {
        $validator = self::make();

        $specification = app($request->getSpecification());

        if ($specification instanceof YamlSpecification) {
            return $validator->fromYaml($specification());
        }

        if ($specification instanceof JsonSpecification) {
            return $validator->fromJson($specification());
        }

        throw new \InvalidArgumentException($request->getSpecification().' should be instance of '.YamlSpecification::class.' or '.JsonSpecification::class);
    }
}
