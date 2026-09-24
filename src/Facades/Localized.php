<?php

declare(strict_types=1);

namespace Belaaredj\FilamentLocalized\Facades;

use Belaaredj\FilamentLocalized\Components\Forms\LocalizedSelect;
use Belaaredj\FilamentLocalized\Components\Infolists\LocalizedTextEntry;
use Belaaredj\FilamentLocalized\Components\Tables\LocalizedTextColumn;
use Belaaredj\FilamentLocalized\FilamentLocalized;
use Filament\Schemas\Components\Tabs;
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
