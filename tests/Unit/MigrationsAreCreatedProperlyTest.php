<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Mudandstars\HistorizeModelChanges\Models\TestModel;
use Mudandstars\HistorizeModelChanges\Actions\GetMigrationName;
use Mudandstars\HistorizeModelChanges\Services\GetHistorizeParams;

//TODO test that the migrations have the right attributes with right types

it('migrations have correct column types', function () {
    Artisan::call('make-historization-files');

    $historizeParamsService = new GetHistorizeParams();
    $historizeParams = $historizeParamsService->getArray(app_path('Models/TraitTestModel.php'));

    foreach (array_keys($historizeParams) as $model) {
        $migrationAction = new GetMigrationName();
        $migrationName = $migrationAction->execute($model);
        $migrationPath = base_path('database/migrations/'.$migrationName);

        expect(file_exists($migrationPath))->toBeTrue();
        expect(str_contains(file_get_contents($migrationPath), 'Custom Historization Migration'))->toBeTrue();
        expect(str_contains(file_get_contents($migrationPath), "$this->string('string');"))->toBeTrue();

        unlink($migrationPath);
    }
});

it("migration command creates correct migrations' files", function () {
    Artisan::call('make-historization-files');

    $historizeParamsService = new GetHistorizeParams();
    $historizeParams = $historizeParamsService->getArray(app_path('Models/TraitTestModel.php'));

    foreach (array_keys($historizeParams) as $model) {
        $migrationAction = new GetMigrationName();
        $migrationName = $migrationAction->execute($model);
        $migrationPath = base_path('database/migrations/'.$migrationName);

        expect(file_exists($migrationPath))->toBeTrue();
        expect(str_contains(file_get_contents($migrationPath), 'Custom Historization Migration'))->toBeTrue();

        unlink($migrationPath);
    }
});
