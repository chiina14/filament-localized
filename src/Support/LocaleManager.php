<?php

declare(strict_types=1);

namespace Belaaredj\FilamentLocalized\Support;

class LocaleManager
{
    public static function all(): array
    {
        return config(
            'filament-localized.locales',
            [],
        );
    }

    public static function codes(): array
    {
        return array_keys(static::all());
    }

    public static function default(): string
    {
        $default = config(
            'filament-localized.default_locale',
            'fr',
        );

        return in_array($default, static::codes(), true)
            ? $default
            : static::codes()[0];
    }

    public static function fallbacks(): array
    {
        return config(
            'filament-localized.fallback_locales',
            ['fr', 'en', 'ar'],
        );
    }

    public static function current(): string
    {
        return LocaleResolver::resolve();
    }

    public static function label(string $locale): string
    {
        return static::all()[$locale]['label']
            ?? strtoupper($locale);
    }

    public static function short(string $locale): string
    {
        return static::all()[$locale]['short']
            ?? strtoupper($locale);
    }

    public static function direction(string $locale): string
    {
        return static::all()[$locale]['direction']
            ?? 'ltr';
    }

    public static function isRtl(string $locale): bool
    {
        return static::direction($locale) === 'rtl';
    }

    public static function flag(string $locale): ?string
    {
        return static::all()[$locale]['flag'] ?? null;
    }

    public static function searchLocales(): array
    {
        return config(
            'filament-localized.search_locales'
        ) ?? static::codes();
    }

    public static function config(): array
    {
        return config(
            'filament-localized.locale_switcher',
            [],
        );
    }
}
