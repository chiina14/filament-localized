<?php

declare(strict_types=1);

namespace Belaaredj\FilamentLocalized\Components;

use Belaaredj\FilamentLocalized\Support\LocaleManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LocaleSwitcher
{
    public static function switch(
        string $locale,
        ?Request $request = null,
    ): RedirectResponse {
        $request ??= request();

        if (! in_array($locale, LocaleManager::codes(), true)) {
            return self::redirectBack($request);
        }

        if (config('filament-localized.locale_persistence.session', true)) {
            Session::put('filament-localized.locale', $locale);
        }

        app()->setLocale($locale);

        return self::redirectBack($request);
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

    public static function flag(string $locale): ?string
    {
        $flag = LocaleManager::flag($locale);

        return is_string($flag) && $flag !== '' ? $flag : null;
    }

    public static function flagFallback(string $locale): string
    {
        return match (config(
            'filament-localized.locale_switcher.flag_fallback',
            'short',
        )) {
            'label' => LocaleManager::label($locale),
            'none' => '',
            default => LocaleManager::short($locale),
        };
    }

    private static function redirectBack(Request $request): RedirectResponse
    {
        $referer = $request->headers->get('referer');
        $refererHost = is_string($referer)
            ? parse_url($referer, PHP_URL_HOST)
            : null;
        $isRelativeReferer = is_string($referer)
            && str_starts_with($referer, '/')
            && ! str_starts_with($referer, '//');

        if (
            is_string($referer)
            && ($isRelativeReferer || $refererHost === $request->getHost())
        ) {
            return redirect()->to($referer);
        }

        return redirect()->to('/');
    }
}
