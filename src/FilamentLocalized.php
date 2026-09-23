<?php

declare(strict_types=1);

namespace Hamada\FilamentLocalized;

use Filament\Forms\Components\Field;
use Filament\Schemas\Components\Tabs;
use Hamada\FilamentLocalized\Components\Forms\LocalizedSelect;
use Hamada\FilamentLocalized\Components\Forms\LocalizedTabs;
use Hamada\FilamentLocalized\Components\Infolists\LocalizedTextEntry;
use Hamada\FilamentLocalized\Components\Tables\LocalizedTextColumn;

class FilamentLocalized
{
    /**
     * Create localized language tabs.
     *
     * @param  array<int, Field>  $components
     */
    public static function tabs(array $components): Tabs
    {
        return LocalizedTabs::make($components);
    }

    /**
     * Create a localized select.
     */
    public static function select(?string $name = null): LocalizedSelect
    {
        return LocalizedSelect::make($name);
    }

    /**
     * Create a localized table column.
     */
    public static function column(?string $name = null): LocalizedTextColumn
    {
        return LocalizedTextColumn::make($name);
    }

    /**
     * Create a localized infolist entry.
     */
    public static function entry(?string $name = null): LocalizedTextEntry
    {
        return LocalizedTextEntry::make($name);
    }
}
