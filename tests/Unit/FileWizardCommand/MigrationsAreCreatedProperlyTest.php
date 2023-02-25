<?php

namespace Tests\Unit\FileWizardCommand;

use Illuminate\Support\Facades\Artisan;
use Mudandstars\HistorizeModelChanges\Actions\GetCorrespondingMigrationPathAction;
use Mudandstars\HistorizeModelChanges\Actions\GetMigrationNameAction;
use Mudandstars\HistorizeModelChanges\Services\MigrationColumnsService;

it('migrations have correct column types', function () {
    Artisan::call('make-historization-files');

    $historizeParams = parent::getTestModelHistorizeParams();

    foreach (array_keys($historizeParams) as $modelName) {
        $migrationName = GetMigrationNameAction::execute($modelName);
        $migrationPath = base_path('database/migrations/'.$migrationName);

        $migrationColumnService = new MigrationColumnsService();
        $migrationColumns = $migrationColumnService->getArray(GetCorrespondingMigrationPathAction::execute(parent::getModelName()));

        $columnName = $historizeParams[$modelName];
        $type = $migrationColumns[$columnName];

        expect(str_contains(file_get_contents($migrationPath), "use Carbon\Carbon;"))->toBeTrue();
        expect(str_contains(file_get_contents($migrationPath), "\$table->timestampTz('created_at')->default(Carbon::now());"))->toBeTrue();
        expect(str_contains(file_get_contents($migrationPath), '$table->'.$type."('previous_".$columnName."');"))->toBeTrue();
        expect(str_contains(file_get_contents($migrationPath), '$table->'.$type."('new_".$columnName."');"))->toBeTrue();
    }
});

it('migration files have proper foreignIdFor', function () {
    Artisan::call('make-historization-files');

    $historizeParams = parent::getTestModelHistorizeParams();

    foreach (array_keys($historizeParams) as $modelName) {
        $migrationName = GetMigrationNameAction::execute($modelName);
        $migrationPath = base_path('database/migrations/'.$migrationName);

        expect(str_contains(file_get_contents($migrationPath), 'use App\Models\\'.parent::getModelName().';'))->toBeTrue();
        expect(str_contains(file_get_contents($migrationPath), '$table->foreignIdFor('.parent::getModelName().'::class)->constrained();'))->toBeTrue();
    }
});

it("migration command creates correct migrations' files", function () {
    Artisan::call('make-historization-files');

    $historizeParams = parent::getTestModelHistorizeParams();

    foreach (array_keys($historizeParams) as $model) {
        $migrationAction = new GetMigrationNameAction();
        $migrationName = $migrationAction->execute($model);
        $migrationPath = base_path('database/migrations/'.$migrationName);

        expect(file_exists($migrationPath))->toBeTrue();
    }
});
