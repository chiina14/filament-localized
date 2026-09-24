<?php

namespace Belaaredj\FilamentLocalized\Support;

class LocaleManager
{
    /**
     * Get all configured locales.
     *
     * @return array<string, array<string, string>>
     */
    public static function all(): array
    {
        return config('filament-localized.locales', []);
    }

    /**
     * Get locale codes.
     *
     * @return array<int, string>
     */
    public static function codes(): array
    {
        return array_keys(static::all());
    }

    /**
     * Get the default locale.
     */
    public static function default(): string
    {
        return config('filament-localized.default_locale', 'fr');
    }

    /**
     * Get the fallback locale.
     */
    public static function fallbacks(): array
    {
        return config(
            'filament-localized.fallback_locales',
            ['fr', 'en', 'ar'],
        );
    }

    /**
     * Get the current application locale.
     */
    public static function current(): string
    {
        $locale = app()->getLocale();

        if (in_array($locale, static::codes(), true)) {
            return $locale;
        }

        return static::default();
    }

    /**
     * Get locale label.
     */
    public static function label(string $locale): string
    {
        return static::all()[$locale]['label']
            ?? strtoupper($locale);
    }

    /**
     * Get short locale label.
     */
    public static function short(string $locale): string
    {
        return static::all()[$locale]['short']
            ?? strtoupper($locale);
    }

    /**
     * Get locale direction.
     */
    public static function direction(string $locale): string
    {
        return static::all()[$locale]['direction']
            ?? 'ltr';
    }

    /**
     * Determine whether locale is RTL.
     */
    public static function isRtl(string $locale): bool
    {
        return static::direction($locale) === 'rtl';
    }

    /**
     * Get locales used for multilingual search.
     *
     * @return array<int, string>
     */
    public static function searchLocales(): array
    {
        return config('filament-localized.search_locales')
            ?? static::codes();
    }
}
