<?php

declare(strict_types=1);

namespace Belaaredj\FilamentLocalized\Http\Middleware;

use Belaaredj\FilamentLocalized\Support\LocaleResolver;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplyLocale
{
    public function handle(
        Request $request,
        Closure $next,
    ): Response {
        app()->setLocale(LocaleResolver::resolve($request));

        return $next($request);
    }
}
