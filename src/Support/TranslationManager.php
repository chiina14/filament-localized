<?php

declare(strict_types=1);

namespace Hamada\FilamentLocalized\Support;

class TranslationManager
{
    /**
     * Get a translated value from a translations array.
     */
    public static function get(
        mixed $translations,
        ?string $locale = null,
    ): ?string {
        if (! is_array($translations)) {
            return filled($translations)
                ? (string) $translations
                : null;
        }

        foreach (static::fallbackLocales($locale) as $fallbackLocale) {
            $value = $translations[$fallbackLocale] ?? null;

            if (filled($value)) {
                return (string) $value;
            }
        }

        return null;
    }

    /**
     * Get the locale fallback chain.
     *
     * Example:
     * current = ar
     * fallback = fr
     * then en
     * then ar
     */
    public static function fallbackLocales(?string $locale = null): array
    {
        $locale ??= LocaleManager::current();

        $locales = [
            $locale,
            ...LocaleManager::fallbacks(),
            ...LocaleManager::codes(),
        ];

        return array_values(
            array_unique(
                array_filter($locales),
            ),
        );
    }

    /**
     * Determine whether a translation exists.
     */
    public static function has(
        mixed $translations,
        string $locale,
    ): bool {
        if (! is_array($translations)) {
            return false;
        }

        return filled($translations[$locale] ?? null);
    }

    /**
     * Get all locales that contain a translation.
     *
     * @return array<int, string>
     */
    public static function availableLocales(mixed $translations): array
    {
        if (! is_array($translations)) {
            return [];
        }

        return array_values(
            array_filter(
                LocaleManager::codes(),
                fn (string $locale): bool => static::has(
                    $translations,
                    $locale,
                ),
            ),
        );
    }

    /**
     * Determine whether at least one translation exists.
     */
    public static function hasAny(mixed $translations): bool
    {
        return static::availableLocales($translations) !== [];
    }
}
