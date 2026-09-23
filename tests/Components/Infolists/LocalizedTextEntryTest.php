<?php

use Filament\Infolists\Components\TextEntry;
use Hamada\FilamentLocalized\Components\Infolists\LocalizedTextEntry;
use Hamada\FilamentLocalized\Support\TranslationManager;

it('creates a localized text entry', function () {
    $entry = LocalizedTextEntry::make('name');

    expect($entry)
        ->toBeInstanceOf(TextEntry::class)
        ->toBeInstanceOf(LocalizedTextEntry::class);
});

it('uses the configured translation manager', function () {
    app()->setLocale('fr');

    $translations = [
        'ar' => 'الروبوتات',
        'fr' => 'Robotique',
        'en' => 'Robotics',
    ];

    expect(
        TranslationManager::get($translations)
    )->toBe('Robotique');
});

it('uses Arabic translation', function () {
    app()->setLocale('ar');

    expect(
        TranslationManager::get([
            'ar' => 'الروبوتات',
            'fr' => 'Robotique',
            'en' => 'Robotics',
        ])
    )->toBe('الروبوتات');
});

it('uses English translation', function () {
    app()->setLocale('en');

    expect(
        TranslationManager::get([
            'ar' => 'الروبوتات',
            'fr' => 'Robotique',
            'en' => 'Robotics',
        ])
    )->toBe('Robotics');
});

it('falls back to French', function () {
    app()->setLocale('ar');

    expect(
        TranslationManager::get([
            'fr' => 'Robotique',
            'en' => 'Robotics',
        ])
    )->toBe('Robotique');
});

it('falls back to English', function () {
    app()->setLocale('fr');

    expect(
        TranslationManager::get([
            'en' => 'Robotics',
            'ar' => 'الروبوتات',
        ])
    )->toBe('Robotics');
});

it('falls back to Arabic', function () {
    app()->setLocale('fr');

    expect(
        TranslationManager::get([
            'ar' => 'الروبوتات',
        ])
    )->toBe('الروبوتات');
});

it('returns null when no translation exists', function () {
    app()->setLocale('fr');

    expect(
        TranslationManager::get([])
    )->toBeNull();
});
