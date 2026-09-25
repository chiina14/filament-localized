<?php

declare(strict_types=1);

namespace Belaaredj\FilamentLocalized\Support;

use Illuminate\Http\Request;

class LocaleResolver
{
    public static function resolve(?Request $request = null): string
    {
        $request ??= request();

        $locales = LocaleManager::codes();

        // 1. Explicit request selection
        $queryLocale = $request->query('lang')
            ?? $request->route('locale');

        if (
            is_string($queryLocale)
            && in_array($queryLocale, $locales, true)
        ) {
            return $queryLocale;
        }

        // 2. Persisted session selection
        $sessionLocale = session('filament-localized.locale');

        if (
            is_string($sessionLocale)
            && in_array($sessionLocale, $locales, true)
        ) {
            return $sessionLocale;
        }

        // 3. Authenticated user, when explicitly enabled.
        $user = $request->user();
        $userLocale = null;

        if (
            config(
                'filament-localized.locale_persistence.user.enabled',
                false,
            )
            && is_object($user)
            && method_exists($user, 'getAttribute')
        ) {
            $userLocale = $user->getAttribute(
                config(
                    'filament-localized.locale_persistence.user.attribute',
                    'locale',
                ),
            );
        }

        if (
            is_string($userLocale)
            && in_array($userLocale, $locales, true)
        ) {
            return $userLocale;
        }

        // 4. Browser language, when enabled.
        $browserLocale = config(
            'filament-localized.locale_persistence.browser.enabled',
            false,
        )
            ? $request->getPreferredLanguage($locales)
            : null;

        if (
            is_string($browserLocale)
            && in_array($browserLocale, $locales, true)
        ) {
            return $browserLocale;
        }

        // 5. Configured default
        return LocaleManager::default();
    }
}
