<?php

declare(strict_types=1);


use Illuminate\Support\Facades\Route;
use Belaaredj\FilamentLocalized\Components\LocaleSwitcher;


Route::get(
    '/filament-localized/locale/{locale}',
    function (string $locale) {
        return LocaleSwitcher::switch($locale);
    },
)->name('filament-localized.locale');
