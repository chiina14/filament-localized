<?php

declare(strict_types=1);

namespace Hamada\FilamentLocalized\Components\Tables;

use Filament\Tables\Columns\TextColumn;
use Hamada\FilamentLocalized\Support\TranslationManager;
use Hamada\FilamentLocalized\Support\TranslationQuery;
use Illuminate\Database\Eloquent\Builder;

class LocalizedTextColumn extends TextColumn
{
    protected bool $localizedSearchEnabled = false;

    /**
     * Create a localized text column.
     */
    public static function make(?string $name = null): static
    {
        $column = parent::make($name);

        $column->getStateUsing(
            function ($record) use ($name): ?string {
                if (! $name) {
                    return null;
                }

                return TranslationManager::get(
                    data_get($record, $name),
                );
            },
        );

        return $column;
    }

    /**
     * Enable multilingual JSON searching.
     */
    public function localizedSearch(bool $condition = true): static
    {
        $this->localizedSearchEnabled = $condition;

        return $this;
    }

    /**
     * Determine whether multilingual searching is enabled.
     */
    public function isLocalizedSearchEnabled(): bool
    {
        return $this->localizedSearchEnabled;
    }

    /**
     * Apply the table search constraint.
     */
    public function applySearchConstraint(
        Builder $query,
        string $search,
        bool &$isFirst,
    ): Builder {
        if (! $this->localizedSearchEnabled) {
            return parent::applySearchConstraint(
                $query,
                $search,
                $isFirst,
            );
        }

        $name = $this->getName();

        if (! $name) {
            return $query;
        }

        TranslationQuery::search(
            query: $query,
            column: $name,
            search: $search,
        );

        $isFirst = false;

        return $query;
    }
}
