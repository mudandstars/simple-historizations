<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Artisan;

//TODO test that the model files have the proper relationships

it('created models have proper $casts attribute', function () {
    Artisan::call('make-historization-files');

    $historizeParams = parent::getTestModelHistorizeParams();
    $migrationColumns = parent::getTestModelMigrationColumns();

    foreach (array_keys($historizeParams) as $model) {
        $modelPath = app_path('Models/'.$model.'.php');

        $columnName = $historizeParams[$model];
        $columnType = $migrationColumns[$columnName];

        if ($columnType == 'date' || $columnType == 'timestamp' || $columnType == 'timestampTz') {
            expect(str_contains(file_get_contents($modelPath), 'protected $dates = ['))->toBeTrue();
            expect(str_contains(file_get_contents($modelPath), "'created_at',"))->toBeTrue();
            expect(str_contains(file_get_contents($modelPath), $columnName))->toBeTrue();
        }

        if ($columnType == 'boolean') {
            expect(str_contains(file_get_contents($modelPath), 'protected $casts = ['))->toBeTrue();
            expect(str_contains(file_get_contents($modelPath), $columnName."' => 'boolean',"))->toBeTrue();
        }

        unlink($modelPath);
    }
});

it("migration command creates correct models' files", function () {
    Artisan::call('make-historization-files');

    $historizeParams = parent::getTestModelHistorizeParams();

    foreach (array_keys($historizeParams) as $model) {
        $modelPath = app_path('Models/'.$model.'.php');

        expect(file_exists($modelPath))->toBeTrue();

        expect(str_contains(file_get_contents($modelPath), 'protected $guarded = [];'))->toBeTrue();
        expect(str_contains(file_get_contents($modelPath), 'public $timestamps = false;'))->toBeTrue();
        expect(str_contains(file_get_contents($modelPath), 'protected $dates = ['))->toBeTrue();
        expect(str_contains(file_get_contents($modelPath), "'created_at',"))->toBeTrue();

        unlink($modelPath);
    }
});
