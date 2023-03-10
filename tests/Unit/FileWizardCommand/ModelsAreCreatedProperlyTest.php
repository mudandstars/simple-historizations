<?php

namespace Tests\Unit\FileWizardCommand;

use Illuminate\Support\Facades\Artisan;

it('created models have proper relationship', function () {
    Artisan::call('make-historization-files');

    $historizeParams = parent::getTestModelHistorizeParams();

    foreach (array_keys($historizeParams) as $model) {
        $modelPath = app_path('Models/'.$model.'.php');
        $relationshipName = lcfirst(parent::getModelName()).'s';

        expect(str_contains(file_get_contents($modelPath), "use Illuminate\Database\Eloquent\Relations\BelongsTo;"))->toBeTrue();
        expect(str_contains(file_get_contents($modelPath), "use App\Models\\".parent::getModelName().';'))->toBeTrue();
        expect(str_contains(file_get_contents($modelPath), 'public function '.$relationshipName.'(): BelongsTo'))->toBeTrue();
        expect(str_contains(file_get_contents($modelPath), 'return $this->belongsTo('.parent::getModelName().'::class);'))->toBeTrue();
    }
});

it('created models have proper $casts attribute', function () {
    Artisan::call('make-historization-files');

    $historizeParams = parent::getTestModelHistorizeParams();
    $migrationColumns = parent::getTestModelMigrationColumns();

    foreach (array_keys($historizeParams) as $model) {
        $modelPath = app_path('Models/'.$model.'.php');

        $columnName = $historizeParams[$model];
        $columnType = $migrationColumns[$columnName];

        if ($columnType == 'date' || $columnType == 'timestamp' || $columnType == 'timestampTz') {
            expect(str_contains(file_get_contents($modelPath), 'protected $casts = ['))->toBeTrue();
            expect(str_contains(file_get_contents($modelPath), "'created_at' => 'datetime',"))->toBeTrue();
            expect(str_contains(file_get_contents($modelPath), 'previous_'.$columnName."' => 'datetime',"))->toBeTrue();
            expect(str_contains(file_get_contents($modelPath), 'new_'.$columnName."' => 'datetime',"))->toBeTrue();
        }

        if ($columnType == 'boolean') {
            expect(str_contains(file_get_contents($modelPath), 'protected $casts = ['))->toBeTrue();
            expect(str_contains(file_get_contents($modelPath), 'previous_'.$columnName."' => 'boolean',"))->toBeTrue();
            expect(str_contains(file_get_contents($modelPath), 'new_'.$columnName."' => 'boolean',"))->toBeTrue();
        }
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
        expect(str_contains(file_get_contents($modelPath), 'protected $casts = ['))->toBeTrue();
        expect(str_contains(file_get_contents($modelPath), "'created_at' => 'datetime',"))->toBeTrue();
    }
});
