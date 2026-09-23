<?php

namespace Hamada\FilamentLocalized\Support;

use Illuminate\Database\Eloquent\Builder;

class TranslationQuery
{
    /**
     * Add a multilingual JSON search condition.
     */
    public static function search(
        Builder $query,
        string $column,
        string $search,
    ): Builder {
        $locales = LocaleManager::searchLocales();

        $query->where(function (Builder $query) use (
            $column,
            $search,
            $locales,
        ): void {
            foreach ($locales as $locale) {
                $query->orWhere(
                    "{$column}->{$locale}",
                    'like',
                    "%{$search}%",
                );
            }
        });

        return $query;
    }

    /**
     * Add multilingual search to a relationship query.
     */
    public static function modify(
        Builder $query,
        string $column,
        ?string $search = null,
    ): Builder {
        if (blank($search)) {
            return $query;
        }

        return static::search(
            query: $query,
            column: $column,
            search: $search,
        );
    }
}
