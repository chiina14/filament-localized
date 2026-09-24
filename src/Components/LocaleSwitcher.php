<?php

declare(strict_types=1);

namespace Belaaredj\FilamentLocalized\Components;

use Belaaredj\FilamentLocalized\Support\LocaleManager;
use Illuminate\Support\Facades\Session;

class LocaleSwitcher
{
    public static function switch(string $locale): void
    {
        if (! in_array($locale, LocaleManager::codes(), true)) {
            return;
        }

        Session::put(
            'filament-localized.locale',
            $locale,
        );

        app()->setLocale($locale);
    }

    public static function current(): string
    {
        return LocaleManager::current();
    }

    public static function locales(): array
    {
        return LocaleManager::all();
    }

    public static function isEnabled(): bool
    {
        return (bool) (
            LocaleManager::config()['enabled'] ?? true
        );
    }

    public static function showFlag(): bool
    {
        return (bool) (
            LocaleManager::config()['show_flag'] ?? true
        );
    }

    public static function showLabel(): bool
    {
        return (bool) (
            LocaleManager::config()['show_label'] ?? true
        );
    }

    public static function showShort(): bool
    {
        return (bool) (
            LocaleManager::config()['show_short'] ?? false
        );
    }
}
