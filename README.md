# Filament Localized

Multilingual infrastructure for [Filament 5](https://filamentphp.com/), providing reusable localized form fields, relationship selects, table columns, infolist entries, multilingual search, and configurable translation fallbacks.

## Features

- 🌍 Multi-language form tabs
- 🔎 Multilingual table search
- 🔗 Localized relationship selects
- 🔍 Multilingual relationship search
- 📋 Localized table columns
- 📄 Localized infolist entries
- ↔️ RTL/LTR locale support
- 🔄 Configurable translation fallback
- ⚙️ Centralized locale configuration
- 🧩 Reusable API through `Localized`
- 🧪 Tested with Filament 5

## Requirements

- PHP `^8.2`
- Filament `^5.0`
- Laravel application compatible with Filament 5

## Installation

Install the package with Composer:

```bash
composer require Belaaredj/filament-localized
```

Publish the package configuration:

```bash
php artisan vendor:publish --tag=filament-localized-config
```

The configuration file will be available at:

```text
config/filament-localized.php
```

You can then customize the supported locales and fallback behavior.

## Configuration

The package stores translations as JSON objects.

For example:

```json
{
    "ar": "الروبوتات",
    "fr": "Robotique",
    "en": "Robotics"
}
```

The default configuration supports Arabic, French, and English:

```php
'locales' => [

    'ar' => [
        'label' => 'العربية',
        'short' => 'AR',
        'direction' => 'rtl',
    ],

    'fr' => [
        'label' => 'Français',
        'short' => 'FR',
        'direction' => 'ltr',
    ],

    'en' => [
        'label' => 'English',
        'short' => 'EN',
        'direction' => 'ltr',
    ],

],
```

You can add or remove locales according to your application.

For example:

```php
'locales' => [

    'ar' => [
        'label' => 'العربية',
        'short' => 'AR',
        'direction' => 'rtl',
    ],

    'fr' => [
        'label' => 'Français',
        'short' => 'FR',
        'direction' => 'ltr',
    ],

    'en' => [
        'label' => 'English',
        'short' => 'EN',
        'direction' => 'ltr',
    ],

    'de' => [
        'label' => 'Deutsch',
        'short' => 'DE',
        'direction' => 'ltr',
    ],

],
```

## Translation Fallback

The package resolves translations using the following order:

```text
Current locale → fr → en → ar
```

For example, when the current locale is `ar`:

```php
[
    'fr' => 'Robotique',
    'en' => 'Robotics',
]
```

The resolved value will be:

```text
Robotique
```

If French is also unavailable:

```php
[
    'en' => 'Robotics',
]
```

the package will resolve:

```text
Robotics
```

Configure the fallback order in:

```php
'fallback_locales' => [
    'fr',
    'en',
    'ar',
],
```

The current application locale is always checked first.

## Localized Form Tabs

Use `Localized::tabs()` to create language tabs for your form fields.

```php
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Belaaredj\FilamentLocalized\Facades\Localized;

Localized::tabs([
    TextInput::make('name')
        ->label('Name'),

    RichEditor::make('description')
        ->label('Description'),
])
```

The package generates a field structure based on the configured locales.

For example:

```text
name.ar
name.fr
name.en

description.ar
description.fr
description.en
```

The resulting state can be stored directly in a JSON column:

```json
{
    "ar": "الروبوتات",
    "fr": "Robotique",
    "en": "Robotics"
}
```

### Database columns

Your translation fields should normally use a JSON-compatible database column.

For Laravel migrations:

```php
$table->json('name')->nullable();
$table->json('description')->nullable();
```

For MySQL, make sure the database supports JSON columns.

## Localized Select

Use `Localized::select()` for localized relationship options.

```php
use Belaaredj\FilamentLocalized\Facades\Localized;

Localized::select('skill_id')
    ->relationship('skill')
    ->localizedTitle('name')
    ->localizedSearch()
    ->searchable();
```

The option label is resolved using the configured translation fallback.

For example:

```json
{
    "ar": "الروبوتات",
    "fr": "Robotique",
    "en": "Robotics"
}
```

The displayed option automatically follows the current locale and fallback configuration.

### Multiple relationships

The same API works with multiple relationships:

```php
Localized::select('skills')
    ->multiple()
    ->relationship('skills')
    ->localizedTitle('name')
    ->localizedSearch()
    ->searchable();
```

## Localized Relationship Search

When:

```php
->localizedSearch()
```

is enabled, relationship searches are performed across the configured search locales.

For example:

```php
Localized::select('skill_id')
    ->relationship('skill')
    ->localizedTitle('name')
    ->localizedSearch()
    ->searchable();
```

A search can match:

```text
العربية
```

or:

```text
Robotique
```

or:

```text
Robotics
```

depending on the configured search locales.

By default:

```php
'search_locales' => null,
```

means that all configured locales are searched.

You can restrict the locales:

```php
'search_locales' => [
    'ar',
    'fr',
],
```

## Localized Table Columns

Use `Localized::column()` for translated JSON attributes in Filament tables.

```php
use Belaaredj\FilamentLocalized\Facades\Localized;

Localized::column('name')
    ->label('Name');
```

Enable multilingual searching with:

```php
Localized::column('name')
    ->label('Name')
    ->localizedSearch()
    ->searchable();
```

The column automatically resolves the displayed translation using the configured fallback order.

## Localized Infolist Entries

Use `Localized::entry()` for translated attributes in Filament infolists.

```php
use Belaaredj\FilamentLocalized\Facades\Localized;

Localized::entry('description')
    ->label('Description');
```

The displayed value follows the same locale and fallback rules used by the other package components.

## Supported API

The package provides a centralized API:

| Method                | Purpose                                    |
| --------------------- | ------------------------------------------ |
| `Localized::tabs()`   | Create multilingual form tabs              |
| `Localized::select()` | Create localized relationship selects      |
| `Localized::column()` | Display and search localized table columns |
| `Localized::entry()`  | Display localized infolist values          |

The underlying specialized components are also available:

```text
LocalizedTabs
LocalizedSelect
LocalizedTextColumn
LocalizedTextEntry
```

## Filament Panel Plugin

The package also provides a Filament plugin class:

```php
use Belaaredj\FilamentLocalized\FilamentLocalizedPlugin;

$panel
    ->plugin(
        FilamentLocalizedPlugin::make()
    );
```

The plugin is intentionally lightweight. The localized components can be used independently and do not require additional panel-specific configuration.

## Locale Configuration

The complete configuration is available in:

```text
config/filament-localized.php
```

Example:

```php
return [

    'locales' => [

        'ar' => [
            'label' => 'العربية',
            'short' => 'AR',
            'direction' => 'rtl',
        ],

        'fr' => [
            'label' => 'Français',
            'short' => 'FR',
            'direction' => 'ltr',
        ],

        'en' => [
            'label' => 'English',
            'short' => 'EN',
            'direction' => 'ltr',
        ],

    ],

    'default_locale' => 'fr',

    'fallback_locales' => [
        'fr',
        'en',
        'ar',
    ],

    'search_locales' => null,

];
```

### Locale properties

Each locale supports:

| Property    | Description        |
| ----------- | ------------------ |
| `label`     | Full display name  |
| `short`     | Short locale label |
| `direction` | `rtl` or `ltr`     |

For example:

```php
'ar' => [
    'label' => 'العربية',
    'short' => 'AR',
    'direction' => 'rtl',
],
```

## Using the Components Directly

The facade is the recommended API for most applications.

However, the underlying components can also be imported directly.

### Localized Select

```php
use Belaaredj\FilamentLocalized\Components\Forms\LocalizedSelect;

LocalizedSelect::make('skill_id')
    ->relationship('skill')
    ->localizedTitle('name')
    ->localizedSearch()
    ->searchable();
```

### Localized Table Column

```php
use Belaaredj\FilamentLocalized\Components\Tables\LocalizedTextColumn;

LocalizedTextColumn::make('name')
    ->localizedSearch()
    ->searchable();
```

### Localized Infolist Entry

```php
use Belaaredj\FilamentLocalized\Components\Infolists\LocalizedTextEntry;

LocalizedTextEntry::make('name');
```

## Recommended Database Structure

A translated attribute should be stored as JSON.

Example migration:

```php
Schema::create('skills', function (Blueprint $table) {
    $table->id();
    $table->json('name');
    $table->json('description')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

Example Eloquent model:

```php
class Skill extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'name' => 'array',
            'description' => 'array',
            'is_active' => 'boolean',
        ];
    }
}
```

The package does not require a translation-specific database table.

## Testing

The package uses Pest for automated testing.

Run the test suite:

```bash
composer test
```

Run static analysis:

```bash
composer analyse
```

Run code formatting:

```bash
composer lint
```

Run the complete development checks:

```bash
composer test
composer analyse
composer lint
```

## Architecture

The package is organized around a small set of reusable components:

```text
Belaaredj\FilamentLocalized
│
├── Components
│   ├── Forms
│   │   ├── LocalizedTabs
│   │   └── LocalizedSelect
│   │
│   ├── Infolists
│   │   └── LocalizedTextEntry
│   │
│   └── Tables
│       └── LocalizedTextColumn
│
├── Support
│   ├── LocaleManager
│   ├── TranslationManager
│   └── TranslationQuery
│
├── Facades
│   └── Localized
│
├── FilamentLocalized
├── FilamentLocalizedPlugin
└── FilamentLocalizedServiceProvider
```

The package intentionally keeps translation resolution and multilingual querying separate from the UI components, making the underlying functionality reusable across forms, tables, infolists, and relationship fields.

## Contributing

Contributions, bug reports, and feature requests are welcome.

Before submitting a pull request, please make sure the test suite and static analysis pass:

```bash
composer test
composer analyse
composer lint
```

For bugs and feature requests, please use the project's issue tracker.

## License

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.

## Author

Developed by **Belaaredj Ahmed**.

---

**Filament Localized** — reusable multilingual infrastructure for Filament 5.
