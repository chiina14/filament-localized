<?php

use Belaaredj\FilamentLocalized\Components\Forms\LocalizedTabs;
use Belaaredj\FilamentLocalized\Support\LocaleManager;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs;

it('creates a localized tabs component', function () {
    $tabs = LocalizedTabs::make([
        TextInput::make('name'),
    ]);

    expect($tabs)
        ->toBeInstanceOf(Tabs::class);
});

it('uses all configured locales when creating localized tabs', function () {
    expect(LocaleManager::codes())
        ->toBe(['ar', 'fr', 'en']);

    $tabs = LocalizedTabs::make([
        TextInput::make('name'),
    ]);

    expect($tabs)
        ->toBeInstanceOf(Tabs::class);
});

it('keeps the original field unchanged', function () {
    $field = TextInput::make('name');

    LocalizedTabs::make([
        $field,
    ]);

    expect($field->getName())
        ->toBe('name');
});

it('makes the localized tabs span the full width', function () {
    $tabs = LocalizedTabs::make([
        TextInput::make('name'),
    ]);

    expect($tabs->getColumnSpan('default'))
        ->toBe('full');
});

it('uses the configured locale fallback order', function () {
    expect(LocaleManager::fallbacks())
        ->toBe(['fr', 'en', 'ar']);
});
