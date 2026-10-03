<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

it('publishes the migration under the package migration tag', function () {
    $migrationPaths = ServiceProvider::pathsToPublish(
        null,
        'filament-localized-migrations',
    );

    expect(collect($migrationPaths)->keys()->contains(
        fn(string $path): bool => basename($path)
            === 'add_locale_to_users_table.php.stub',
    ))->toBeTrue();
});

it('adds and removes the nullable locale column', function () {
    $migration = require dirname(__DIR__, 2)
        . '/database/migrations/add_locale_to_users_table.php.stub';

    $migration->up();

    expect(Schema::hasColumn('users', 'locale'))->toBeTrue();

    $migration->down();

    expect(Schema::hasColumn('users', 'locale'))->toBeFalse();
});
