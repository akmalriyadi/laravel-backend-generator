<?php

namespace AkmalRiyadi\LaravelBackendGenerator\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \AkmalRiyadi\LaravelBackendGenerator\LaravelBackendGenerator
 */
class LaravelBackendGenerator extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-backend-generator';
        // return \AkmalRiyadi\LaravelBackendGenerator\LaravelBackendGenerator::class;
    }
}
