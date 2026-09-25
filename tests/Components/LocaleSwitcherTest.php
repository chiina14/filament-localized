<?php

use Belaaredj\FilamentLocalized\Components\LocaleSwitcher;
use Belaaredj\FilamentLocalized\Http\Middleware\ApplyLocale;
use Belaaredj\FilamentLocalized\Support\LocaleResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

afterEach(function () {
    session()->forget('filament-localized.locale');
    config()->set('filament-localized.locales.en.flag', '🇬🇧');
    config()->set('filament-localized.locale_switcher.enabled', true);
    config()->set('filament-localized.locale_switcher.show_short', false);
    config()->set('filament-localized.locale_persistence.browser.enabled', false);
});

it('registers the locale switching route', function () {
    expect(Route::has('filament-localized.locale'))->toBeTrue();
});

it('switches to a configured locale and persists it in the session', function () {
    $response = $this->withHeader('Referer', url('/dashboard'))
        ->get(route('filament-localized.locale', ['locale' => 'en']));

    $response->assertRedirect(url('/dashboard'));
    $this->assertSame('en', session('filament-localized.locale'));
    expect(LocaleSwitcher::current())->toBe('en');
});

it('rejects unsupported locales without changing the session', function () {
    session(['filament-localized.locale' => 'fr']);

    $response = $this->get(route('filament-localized.locale', ['locale' => 'xx']));

    $response->assertRedirect('/');
    $this->assertSame('fr', session('filament-localized.locale'));
});

it('resolves an explicit locale before the session locale', function () {
    session(['filament-localized.locale' => 'fr']);
    $request = Request::create('/?lang=en');

    expect(LocaleResolver::resolve($request))->toBe('en');
});

it('uses browser language only when browser detection is enabled', function () {
    config()->set('filament-localized.locale_persistence.browser.enabled', false);
    $request = Request::create('/');
    $request->headers->set('Accept-Language', 'ar');

    expect(LocaleResolver::resolve($request))->toBe('fr');

    config()->set('filament-localized.locale_persistence.browser.enabled', true);

    expect(LocaleResolver::resolve($request))->toBe('ar');
});

it('applies the resolved locale in middleware', function () {
    session(['filament-localized.locale' => 'ar']);
    $request = Request::create('/');
    $request->setLaravelSession(app('session.store'));

    app(ApplyLocale::class)->handle(
        $request,
        fn () => response('ok'),
    );

    expect(app()->getLocale())->toBe('ar');
});

it('renders configured labels and a fallback when a flag is unavailable', function () {
    session(['filament-localized.locale' => 'en']);
    config()->set('filament-localized.locales.en.flag', null);
    config()->set('filament-localized.locale_switcher.show_short', true);

    $html = view('filament-localized::components.locale-switcher')->render();

    expect($html)
        ->toContain('English')
        ->toContain('EN');
});

it('can disable the switcher through configuration', function () {
    config()->set('filament-localized.locale_switcher.enabled', false);

    expect(view('filament-localized::components.locale-switcher')->render())
        ->toBe('');
});

it('exposes direction metadata for rtl and ltr locales', function () {
    expect(LocaleSwitcher::locales()['ar']['direction'])->toBe('rtl')
        ->and(LocaleSwitcher::locales()['en']['direction'])->toBe('ltr');
});
