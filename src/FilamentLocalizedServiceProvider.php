<?php

declare(strict_types=1);

namespace Belaaredj\FilamentLocalized;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Illuminate\Support\Facades\Route;

class FilamentLocalizedServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-localized';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasViews()
            ->hasInstallCommand(function (InstallCommand $command): void {
                $command
                    ->publishConfigFile()
                    ->askToStarRepoOnGitHub('Belaaredj/filament-localized');
            });
    }

    public function packageBooted(): void
    {
        Route::get('/filament-localized/locale/{locale}', function (string $locale) {
            \Belaaredj\FilamentLocalized\Components\LocaleSwitcher::switch($locale);

            return redirect()->back();
        })->name('filament-localized.locale');
    }
}
