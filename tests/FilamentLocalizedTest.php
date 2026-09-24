<?php

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Tabs;
use Filament\Tables\Columns\TextColumn;
use Belaaredj\FilamentLocalized\Components\Infolists\LocalizedTextEntry;
use Belaaredj\FilamentLocalized\Facades\Localized;

it('creates localized tabs through the facade', function () {
    $tabs = Localized::tabs([
        TextInput::make('name'),
    ]);

    expect($tabs)->toBeInstanceOf(Tabs::class);
});

it('creates localized table columns through the facade', function () {
    $column = Localized::column('name');

    expect($column)
        ->toBeInstanceOf(TextColumn::class);
});

it('creates localized selects through the facade', function () {
    $select = Localized::select('skill_id');

    expect($select)
        ->toBeInstanceOf(Select::class);
});

it('creates localized entries through the facade', function () {
    $entry = Localized::entry('name');

    expect($entry)
        ->toBeInstanceOf(LocalizedTextEntry::class)
        ->toBeInstanceOf(TextEntry::class);

    expect($entry->getName())->toBe('name');
});
