<?php

declare(strict_types=1);

namespace Belaaredj\FilamentLocalized\Components\Infolists;

use Filament\Infolists\Components\TextEntry;
use Belaaredj\FilamentLocalized\Support\TranslationManager;

class LocalizedTextEntry extends TextEntry
{
    /**
     * Create a localized text entry.
     */
    public static function make(?string $name = null): static
    {
        $entry = parent::make($name);

        $entry->state(
            function ($record) use ($name): ?string {
                if (! $name) {
                    return null;
                }

                return TranslationManager::get(
                    data_get($record, $name),
                );
            },
        );

        return $entry;
    }
}
