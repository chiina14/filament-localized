<?php

use Belaaredj\FilamentLocalized\Support\TranslationManager;

it('returns the current locale translation', function () {
    app()->setLocale('ar');

    $translations = [
        'ar' => 'الروبوتات',
        'fr' => 'Robotique',
        'en' => 'Robotics',
    ];

    expect(
        TranslationManager::get($translations)
    )->toBe('الروبوتات');
});

it('falls back to french when current locale is missing', function () {
    app()->setLocale('ar');

    $translations = [
        'fr' => 'Robotique',
        'en' => 'Robotics',
    ];

    expect(
        TranslationManager::get($translations)
    )->toBe('Robotique');
});

it('falls back to english when french is missing', function () {
    app()->setLocale('ar');

    $translations = [
        'en' => 'Robotics',
    ];

    expect(
        TranslationManager::get($translations)
    )->toBe('Robotics');
});

it('returns null when no translation exists', function () {
    app()->setLocale('ar');

    expect(
        TranslationManager::get([])
    )->toBeNull();
});

it('returns available locales', function () {
    $translations = [
        'ar' => 'الروبوتات',
        'fr' => 'Robotique',
        'en' => '',
    ];

    expect(
        TranslationManager::availableLocales($translations)
    )->toBe(['ar', 'fr']);
});

it('respects the fallback order from the current locale', function () {
    app()->setLocale('fr');

    $translations = [
        'en' => 'Robotics',
        'ar' => 'الروبوتات',
    ];

    expect(
        TranslationManager::get($translations)
    )->toBe('Robotics');
});
