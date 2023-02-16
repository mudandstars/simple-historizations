<?php

namespace Tests\Unit;

use Mudandstars\HistorizeModelChanges\Actions\GetMigrationName;
use Mudandstars\HistorizeModelChanges\Services\GetMigrationColumns;

it('GetHistorizeParam Service returns the proper array', function () {
    $getMigrationNameAction = new GetMigrationName();
    $getMigrationColumnsService = new GetMigrationColumns();

    $migrationColumns = $getMigrationColumnsService->getArray(base_path('database/migrations/'.$getMigrationNameAction->execute(parent::getModelName())));

    expect($migrationColumns['string'])->toBe('string');
    expect($migrationColumns['integer'])->toBe('integer');
    expect($migrationColumns['boolean'])->toBe('boolean');
    expect($migrationColumns['date'])->toBe('date');
    expect($migrationColumns['timestamp'])->toBe('timestamp');
    expect($migrationColumns['timestampTz'])->toBe('timestampTz');

    expect($migrationColumns)->toHaveCount(6);
});
