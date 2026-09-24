<?php

declare(strict_types=1);

namespace Belaaredj\FilamentLocalized;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentLocalizedServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-localized';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasInstallCommand(function (InstallCommand $command): void {
                $command
                    ->publishConfigFile()
                    ->askToStarRepoOnGitHub('Belaaredj/filament-localized');
            });
    }
}
