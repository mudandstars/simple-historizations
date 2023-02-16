<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Artisan;
use Mudandstars\HistorizeModelChanges\Services\GetHistorizeParams;

//TODO test that the model files have the proper relationships
//TODO test that the model files are properly casted

it("migration command creates correct models' files", function () {
    Artisan::call('make-historization-files');

    $historizeParamsService = new GetHistorizeParams();
    $historizeParams = $historizeParamsService->getArray(app_path('Models/'.parent::getModelName().'.php'));

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
