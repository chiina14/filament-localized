<?php

namespace Hamada\FilamentLocalized\Components\Forms;

use Filament\Forms\Components\Field;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Hamada\FilamentLocalized\Support\LocaleManager;

class LocalizedTabs
{
    /**
     * Create language tabs from Filament form fields.
     *
     * @param  array<int, Field>  $components
     */
    public static function make(array $components): Tabs
    {
        $tabs = [];

        foreach (LocaleManager::codes() as $locale) {
            $tabs[] = static::makeTab(
                locale: $locale,
                components: $components,
            );
        }

        return Tabs::make('localized')
            ->tabs($tabs)
            ->columnSpanFull();
    }

    /**
     * Create a tab for a specific locale.
     *
     * @param  array<int, Field>  $components
     */
    protected static function makeTab(
        string $locale,
        array $components,
    ): Tab {
        return Tab::make(
            LocaleManager::label($locale),
        )->schema(
            array_map(
                fn (Field $component): Field => static::localizeComponent(
                    $component,
                    $locale,
                ),
                $components,
            ),
        );
    }

    /**
     * Create a localized copy of a form field.
     */
    protected static function localizeComponent(
        Field $component,
        string $locale,
    ): Field {
        $localized = clone $component;

        $localized->statePath(
            "{$component->getName()}.{$locale}",
        );

        return $localized;
    }
}
