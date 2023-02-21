<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Artisan;
use Mudandstars\HistorizeModelChanges\Actions\GetCorrespondingMigrationPath;
use Mudandstars\HistorizeModelChanges\Actions\GetMigrationName;
use Mudandstars\HistorizeModelChanges\Services\GetMigrationColumns;

it('migrations have correct column types', function () {
    Artisan::call('make-historization-files');

    $historizeParams = parent::getTestModelHistorizeParams();

    foreach (array_keys($historizeParams) as $model) {
        $migrationAction = new GetMigrationName();
        $migrationName = $migrationAction->execute($model);
        $migrationPath = base_path('database/migrations/'.$migrationName);

        $migrationColumnService = new GetMigrationColumns();
        $migrationColumns = $migrationColumnService->getArray(GetCorrespondingMigrationPath::execute(parent::getModelName()));

        $name = $historizeParams[$model];
        $type = $migrationColumns[$name];

        expect(str_contains(file_get_contents($migrationPath), "use Carbon\Carbon;"))->toBeTrue();
        expect(str_contains(file_get_contents($migrationPath), "\$table->timestampTz('created_at')->default(Carbon::now());"))->toBeTrue();
        expect(str_contains(file_get_contents($migrationPath), '$table->'.$type."('previous_".$name."');"))->toBeTrue();
        expect(str_contains(file_get_contents($migrationPath), '$table->'.$type."('new_".$name."');"))->toBeTrue();

        // unlink($migrationPath);
    }
});

it('migration files have proper foreignIdFor', function () {
    Artisan::call('make-historization-files');

    $historizeParams = parent::getTestModelHistorizeParams();

    foreach (array_keys($historizeParams) as $model) {
        $migrationNameAction = new GetMigrationName();
        $migrationName = $migrationNameAction->execute($model);
        $migrationPath = base_path('database/migrations/'.$migrationName);

        expect(str_contains(file_get_contents($migrationPath), 'use App\Models\\'.parent::getModelName().';'))->toBeTrue();
        expect(str_contains(file_get_contents($migrationPath), '$table->foreignIdFor('.parent::getModelName().'::class)->constrained();'))->toBeTrue();

        // unlink($migrationPath);
    }
});

it("migration command creates correct migrations' files", function () {
    Artisan::call('make-historization-files');

    $historizeParams = parent::getTestModelHistorizeParams();

    foreach (array_keys($historizeParams) as $model) {
        $migrationAction = new GetMigrationName();
        $migrationName = $migrationAction->execute($model);
        $migrationPath = base_path('database/migrations/'.$migrationName);

        expect(file_exists($migrationPath))->toBeTrue();

        // unlink($migrationPath);
    }
});
