<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Artisan;
use Mudandstars\HistorizeModelChanges\Actions\GetMigrationName;
use Mudandstars\HistorizeModelChanges\Services\GetHistorizeParams;
use Mudandstars\HistorizeModelChanges\Services\GetMigrationColumns;
use Mudandstars\HistorizeModelChanges\Actions\GetCorrespondingMigrationPath;

it('migrations have correct column types', function () {
    Artisan::call('make-historization-files');

    $historizeParamsService = new GetHistorizeParams();
    $historizeParams = $historizeParamsService->getArray(app_path('Models/'.parent::getModelName().'.php'));

    foreach (array_keys($historizeParams) as $model) {
        $migrationAction = new GetMigrationName();
        $migrationName = $migrationAction->execute($model);
        $migrationPath = base_path('database/migrations/'.$migrationName);

        $migrationColumnService = new GetMigrationColumns();
        $migrationColumns = $migrationColumnService->getArray(GetCorrespondingMigrationPath::execute(parent::getModelName()));

        $name = $historizeParams[$model];
        $type = $migrationColumns[$name];

        expect(str_contains(file_get_contents($migrationPath), "\$table->".$type."('previous_".$name."');"))->toBeTrue();
        expect(str_contains(file_get_contents($migrationPath), "\$table->".$type."('new_".$name."');"))->toBeTrue();

        unlink($migrationPath);
    }
});

it("migration command creates correct migrations' files", function () {
    Artisan::call('make-historization-files');

    $historizeParamsService = new GetHistorizeParams();
    $historizeParams = $historizeParamsService->getArray(app_path('Models/'.parent::getModelName().'.php'));

    foreach (array_keys($historizeParams) as $model) {
        $migrationAction = new GetMigrationName();
        $migrationName = $migrationAction->execute($model);
        $migrationPath = base_path('database/migrations/'.$migrationName);

        expect(file_exists($migrationPath))->toBeTrue();

        unlink($migrationPath);
    }
});
