<?php

namespace Tests\Unit\Services;

it('GetHistorizeParam Service returns the proper array', function () {
    $migrationColumns = parent::getTestModelMigrationColumns();

    expect($migrationColumns['string'])->toBe('string');
    expect($migrationColumns['integer'])->toBe('integer');
    expect($migrationColumns['boolean'])->toBe('boolean');
    expect($migrationColumns['date'])->toBe('date');
    expect($migrationColumns['timestamp'])->toBe('timestamp');
    expect($migrationColumns['timestampTz'])->toBe('timestampTz');

    expect($migrationColumns)->toHaveCount(7);
});
