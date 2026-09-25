<?php

declare(strict_types=1);

use Belaaredj\FilamentLocalized\Components\LocaleSwitcher;
use Illuminate\Support\Facades\Route;

Route::get(
    '/filament-localized/locale/{locale}',
    function (string $locale) {
        return LocaleSwitcher::switch($locale, request());
    },
)->name('filament-localized.locale');
