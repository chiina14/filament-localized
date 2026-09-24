# Changelog

All notable changes to `filament-localized` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## Filament Localized v1.1.1 - 2026-09-24

Fix Route

## Filament Localized v1.1.0 - 2026-09-24

### Added

* Integrated locale switcher for Filament 5.
* Configurable language switcher in the Filament topbar.
* Session-based locale persistence.
* Configurable locale labels, short codes, and flags.
* Locale validation before switching.
* Middleware for applying the selected locale.
* RTL/LTR locale metadata.
* Improved multilingual localization infrastructure.

### Compatibility

* PHP `^8.2`
* Filament `^5.0`
* Laravel 12

### Installation

```bash
composer require belaaredj/filament-localized


```
### Configuration

The locale switcher can be configured through the package configuration:

```php
'locale_switcher' => [
    'enabled' => true,
    'show_flag' => true,
    'show_label' => true,
    'show_short' => false,
],


```
## [1.0.0] - 2026-09-24

### Added

- Multilingual form tabs with configurable locales.
- Localized relationship selects.
- Multilingual relationship searching.
- Localized table columns.
- Multilingual table searching.
- Localized infolist entries.
- Configurable translation fallback order.
- Configurable locales with RTL/LTR direction support.
- Configurable search locales.
- Centralized `Localized` facade API.
- Filament 5 panel plugin integration.
- Automated Pest test suite.
- PHPStan static analysis.
- Laravel Pint code formatting.
