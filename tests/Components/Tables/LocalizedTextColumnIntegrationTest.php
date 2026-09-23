<?php

use Filament\Tables\Columns\TextColumn;
use Hamada\FilamentLocalized\Components\Tables\LocalizedTextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

beforeEach(function (): void {
    Schema::create('test_localized_table_skills', function (Blueprint $table): void {
        $table->id();
        $table->json('name')->nullable();
        $table->timestamps();
    });
});

afterEach(function (): void {
    Schema::dropIfExists('test_localized_table_skills');
});

it('creates a localized text column', function () {
    $column = LocalizedTextColumn::make('name');

    expect($column)
        ->toBeInstanceOf(TextColumn::class)
        ->toBeInstanceOf(LocalizedTextColumn::class);
});

it('does not enable localized search by default', function () {
    $column = LocalizedTextColumn::make('name');

    expect($column->isLocalizedSearchEnabled())
        ->toBeFalse();
});

it('enables localized search', function () {
    $column = LocalizedTextColumn::make('name')
        ->localizedSearch();

    expect($column->isLocalizedSearchEnabled())
        ->toBeTrue();
});

it('searches Arabic translations', function () {
    $robotics = TestLocalizedTableSkill::create([
        'name' => [
            'ar' => 'الروبوتات',
            'fr' => 'Robotique',
            'en' => 'Robotics',
        ],
    ]);

    $programming = TestLocalizedTableSkill::create([
        'name' => [
            'ar' => 'البرمجة',
            'fr' => 'Programmation',
            'en' => 'Programming',
        ],
    ]);

    $query = TestLocalizedTableSkill::query();

    $column = LocalizedTextColumn::make('name')
        ->localizedSearch();

    $isFirst = true;

    $column->applySearchConstraint(
        $query,
        'الروبوتات',
        $isFirst,
    );

    expect($query->pluck('id')->all())
        ->toBe([$robotics->id])
        ->and($programming->id)
        ->not->toBe($robotics->id);
});

it('searches French translations', function () {
    $robotics = TestLocalizedTableSkill::create([
        'name' => [
            'ar' => 'الروبوتات',
            'fr' => 'Robotique',
            'en' => 'Robotics',
        ],
    ]);

    TestLocalizedTableSkill::create([
        'name' => [
            'ar' => 'البرمجة',
            'fr' => 'Programmation',
            'en' => 'Programming',
        ],
    ]);

    $query = TestLocalizedTableSkill::query();

    $column = LocalizedTextColumn::make('name')
        ->localizedSearch();

    $isFirst = true;

    $column->applySearchConstraint(
        $query,
        'Robotique',
        $isFirst,
    );

    expect($query->pluck('id')->all())
        ->toBe([$robotics->id]);
});

it('searches English translations', function () {
    $robotics = TestLocalizedTableSkill::create([
        'name' => [
            'ar' => 'الروبوتات',
            'fr' => 'Robotique',
            'en' => 'Robotics',
        ],
    ]);

    TestLocalizedTableSkill::create([
        'name' => [
            'ar' => 'البرمجة',
            'fr' => 'Programmation',
            'en' => 'Programming',
        ],
    ]);

    $query = TestLocalizedTableSkill::query();

    $column = LocalizedTextColumn::make('name')
        ->localizedSearch();

    $isFirst = true;

    $column->applySearchConstraint(
        $query,
        'Robotics',
        $isFirst,
    );

    expect($query->pluck('id')->all())
        ->toBe([$robotics->id]);
});

it('searches partial translations', function () {
    $robotics = TestLocalizedTableSkill::create([
        'name' => [
            'ar' => 'الروبوتات',
            'fr' => 'Robotique',
            'en' => 'Robotics',
        ],
    ]);

    TestLocalizedTableSkill::create([
        'name' => [
            'ar' => 'البرمجة',
            'fr' => 'Programmation',
            'en' => 'Programming',
        ],
    ]);

    $query = TestLocalizedTableSkill::query();

    $column = LocalizedTextColumn::make('name')
        ->localizedSearch();

    $isFirst = true;

    $column->applySearchConstraint(
        $query,
        'Robot',
        $isFirst,
    );

    expect($query->pluck('id')->all())
        ->toBe([$robotics->id]);
});

it('returns no results for an unknown translation', function () {
    TestLocalizedTableSkill::create([
        'name' => [
            'ar' => 'الروبوتات',
            'fr' => 'Robotique',
            'en' => 'Robotics',
        ],
    ]);

    $query = TestLocalizedTableSkill::query();

    $column = LocalizedTextColumn::make('name')
        ->localizedSearch();

    $isFirst = true;

    $column->applySearchConstraint(
        $query,
        'Unknown',
        $isFirst,
    );

    expect($query->pluck('id')->all())
        ->toBe([]);
});

it('displays the translation for the current locale', function () {
    app()->setLocale('fr');

    $record = TestLocalizedTableSkill::create([
        'name' => [
            'ar' => 'الروبوتات',
            'fr' => 'Robotique',
            'en' => 'Robotics',
        ],
    ]);

    $column = LocalizedTextColumn::make('name');

    $callback = $column->getGetStateUsingCallback();

    expect($callback)
        ->toBeInstanceOf(Closure::class)
        ->and($callback($record))
        ->toBe('Robotique');
});

it('displays the Arabic translation for Arabic locale', function () {
    app()->setLocale('ar');

    $record = TestLocalizedTableSkill::create([
        'name' => [
            'ar' => 'الروبوتات',
            'fr' => 'Robotique',
            'en' => 'Robotics',
        ],
    ]);

    $column = LocalizedTextColumn::make('name');

    $callback = $column->getGetStateUsingCallback();

    expect($callback($record))
        ->toBe('الروبوتات');
});

it('displays the English translation for English locale', function () {
    app()->setLocale('en');

    $record = TestLocalizedTableSkill::create([
        'name' => [
            'ar' => 'الروبوتات',
            'fr' => 'Robotique',
            'en' => 'Robotics',
        ],
    ]);

    $column = LocalizedTextColumn::make('name');

    $callback = $column->getGetStateUsingCallback();

    expect($callback($record))
        ->toBe('Robotics');
});

it('falls back to French when the current locale is unavailable', function () {
    app()->setLocale('ar');

    $record = TestLocalizedTableSkill::create([
        'name' => [
            'fr' => 'Robotique',
            'en' => 'Robotics',
        ],
    ]);

    $column = LocalizedTextColumn::make('name');

    $callback = $column->getGetStateUsingCallback();

    expect($callback($record))
        ->toBe('Robotique');
});

it('falls back to English when French is unavailable', function () {
    app()->setLocale('fr');

    $record = TestLocalizedTableSkill::create([
        'name' => [
            'en' => 'Robotics',
            'ar' => 'الروبوتات',
        ],
    ]);

    $column = LocalizedTextColumn::make('name');

    $callback = $column->getGetStateUsingCallback();

    expect($callback($record))
        ->toBe('Robotics');
});

it('falls back to Arabic when other fallback locales are unavailable', function () {
    app()->setLocale('fr');

    $record = TestLocalizedTableSkill::create([
        'name' => [
            'ar' => 'الروبوتات',
        ],
    ]);

    $column = LocalizedTextColumn::make('name');

    $callback = $column->getGetStateUsingCallback();

    expect($callback($record))
        ->toBe('الروبوتات');
});

it('returns null when no translation is available', function () {
    app()->setLocale('fr');

    $record = TestLocalizedTableSkill::create([
        'name' => [],
    ]);

    $column = LocalizedTextColumn::make('name');

    $callback = $column->getGetStateUsingCallback();

    expect($callback($record))
        ->toBeNull();
});

/*
|--------------------------------------------------------------------------
| Test model
|--------------------------------------------------------------------------
*/

class TestLocalizedTableSkill extends Model
{
    protected $table = 'test_localized_table_skills';

    protected $guarded = [];

    public $timestamps = true;

    protected function casts(): array
    {
        return [
            'name' => 'array',
        ];
    }
}
