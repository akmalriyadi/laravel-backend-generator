<?php

namespace AkmalRiyadi\LaravelBackendGenerator;


use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use AkmalRiyadi\LaravelBackendGenerator\Commands\MakeRequestAkm;
use AkmalRiyadi\LaravelBackendGenerator\Commands\MakeServiceAkm;
use AkmalRiyadi\LaravelBackendGenerator\Commands\MakeControllerAkm;
use AkmalRiyadi\LaravelBackendGenerator\Commands\MakeRepositoryAkm;

class LaravelBackendGeneratorServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-backend-generator')
            ->hasConfigFile()
            ->hasViews()
            ->hasCommand(MakeRepositoryAkm::class)
            ->hasCommand(MakeControllerAkm::class)
            ->hasCommand(MakeRequestAkm::class)
            ->hasCommand(MakeServiceAkm::class);
    }
}
