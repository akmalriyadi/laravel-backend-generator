<?php

namespace AkmalRiyadi\LaravelBackendGenerator;


use AkmalRiyadi\LaravelBackendGenerator\Commands\MakeRepositoryAkm;
use AkmalRiyadi\LaravelBackendGenerator\Commands\MakeServiceAkm;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
            ->hasCommand(MakeServiceAkm::class);
    }
}
