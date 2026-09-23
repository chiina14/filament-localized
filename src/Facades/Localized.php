<?php

declare(strict_types=1);

namespace Hamada\FilamentLocalized\Facades;

use Filament\Schemas\Components\Tabs;
use Hamada\FilamentLocalized\Components\Forms\LocalizedSelect;
use Hamada\FilamentLocalized\Components\Infolists\LocalizedTextEntry;
use Hamada\FilamentLocalized\Components\Tables\LocalizedTextColumn;
use Hamada\FilamentLocalized\FilamentLocalized;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Tabs tabs(array $components)
 * @method static LocalizedSelect select(?string $name = null)
 * @method static LocalizedTextColumn column(?string $name = null)
 * @method static LocalizedTextEntry entry(?string $name = null)
 *
 * @see FilamentLocalized
 */
class Localized extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return FilamentLocalized::class;
    }
}
