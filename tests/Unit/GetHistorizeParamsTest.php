<?php

namespace Tests\Unit;

use Mudandstars\HistorizeModelChanges\Services\GetHistorizeParams;

it('GetHistorizeParam Service returns the proper array', function () {
    parent::createModelThatUsesTrait();

    $getHistorizeParams = new GetHistorizeParams();
    $historizeParams = $getHistorizeParams->getArray(app_path('Models/TraitTestModel.php'));

    expect($historizeParams['TestHistorization'])->toBe('string');
    expect($historizeParams['AnotherHistorization'])->toBe('integer');
});
