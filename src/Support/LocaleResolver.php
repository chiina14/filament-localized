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

        // 1. Query parameter
        $queryLocale = $request->query('lang');

        if (
            is_string($queryLocale)
            && in_array($queryLocale, $locales, true)
        ) {
            return $queryLocale;
        }

        // 2. Session
        $sessionLocale = session('filament-localized.locale');

        if (
            is_string($sessionLocale)
            && in_array($sessionLocale, $locales, true)
        ) {
            return $sessionLocale;
        }

        // 3. Cookie
        $cookieLocale = $request->cookie(
            'filament-localized.locale'
        );

        if (
            is_string($cookieLocale)
            && in_array($cookieLocale, $locales, true)
        ) {
            return $cookieLocale;
        }

        // 4. Authenticated user
        $userLocale = auth()->user()?->getAttribute(
            'locale'
        );

        if (
            is_string($userLocale)
            && in_array($userLocale, $locales, true)
        ) {
            return $userLocale;
        }

        // 5. Browser language
        $browserLocale = $request->getPreferredLanguage(
            $locales
        );

        if (
            is_string($browserLocale)
            && in_array($browserLocale, $locales, true)
        ) {
            return $browserLocale;
        }

        // 6. Default
        return LocaleManager::default();
    }
}
