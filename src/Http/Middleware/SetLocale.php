<?php

declare(strict_types=1);

namespace Belaaredj\FilamentLocalized\Http\Middleware;

use Belaaredj\FilamentLocalized\Support\LocaleManager;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(
        Request $request,
        Closure $next,
    ): Response {
        $locale = session('filament-localized.locale');

        if (
            ! is_string($locale)
            || ! in_array($locale, LocaleManager::codes(), true)
        ) {
            $locale = LocaleManager::default();
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
