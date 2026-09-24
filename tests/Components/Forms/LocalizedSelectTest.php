<?php

use Filament\Forms\Components\Select;
use Belaaredj\FilamentLocalized\Components\Forms\LocalizedSelect;

it('creates a localized select', function () {
    $select = LocalizedSelect::make('skill_id');

    expect($select)
        ->toBeInstanceOf(LocalizedSelect::class)
        ->toBeInstanceOf(Select::class);

    expect($select->getName())
        ->toBe('skill_id');
});

it('uses name as the default localized title attribute', function () {
    $select = LocalizedSelect::make('skill_id');

    expect($select->getLocalizedTitleAttribute())
        ->toBe('name');
});

it('allows a custom localized title attribute', function () {
    $select = LocalizedSelect::make('skill_id')
        ->localizedTitle('title');

    expect($select->getLocalizedTitleAttribute())
        ->toBe('title');
});

it('supports multiple selections', function () {
    $select = LocalizedSelect::make('skills')
        ->multiple();

    expect($select->isMultiple())
        ->toBeTrue();
});

it('supports localized relationship search', function () {
    $select = LocalizedSelect::make('skill_id')
        ->localizedTitle('name')
        ->localizedSearch();

    expect($select->isLocalizedSearchEnabled())
        ->toBeTrue();
});

it('allows disabling localized relationship search', function () {
    $select = LocalizedSelect::make('skill_id')
        ->localizedSearch(false);

    expect($select->isLocalizedSearchEnabled())
        ->toBeFalse();
});
