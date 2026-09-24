<?php

namespace Hamada\FilamentLocalized\Components\Forms;

use Filament\Forms\Components\Select;
use Hamada\FilamentLocalized\Support\TranslationManager;
use Hamada\FilamentLocalized\Support\TranslationQuery;
use Illuminate\Database\Eloquent\Builder;

class LocalizedSelect extends Select
{
    protected string $localizedTitleAttribute = 'name';

    protected bool $localizedSearchEnabled = false;

    /**
     * Create a localized select.
     */
    public static function make(?string $name = null): static
    {
        $select = parent::make($name);

        $select->getOptionLabelFromRecordUsing(
            fn ($record): ?string => TranslationManager::get(
                $record->getAttribute(
                    $select->localizedTitleAttribute,
                ),
            ),
        );

        return $select;
    }

    /**
     * Set the translated attribute used for option labels.
     */
    public function localizedTitle(string $attribute): static
    {
        $this->localizedTitleAttribute = $attribute;

        return $this;
    }

    /**
     * Enable multilingual relationship searching.
     */
    public function localizedSearch(bool $condition = true): static
    {
        $this->localizedSearchEnabled = $condition;

        return $this;
    }

    /**
     * Apply the Select search constraint.
     */
    public function applySearchConstraint(
        Builder $query,
        string $search,
    ): Builder {
        if (! $this->localizedSearchEnabled) {
            return parent::applySearchConstraint(
                $query,
                $search,
            );
        }

        return TranslationQuery::modify(
            query: $query,
            column: $this->localizedTitleAttribute,
            search: $search,
        );
    }

    /**
     * Get the translated title attribute.
     */
    public function getLocalizedTitleAttribute(): string
    {
        return $this->localizedTitleAttribute;
    }

    /**
     * Determine whether multilingual searching is enabled.
     */
    public function isLocalizedSearchEnabled(): bool
    {
        return $this->localizedSearchEnabled;
    }
}
