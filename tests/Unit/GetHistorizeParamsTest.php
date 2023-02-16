<?php

namespace Tests\Unit;

use Mudandstars\HistorizeModelChanges\Services\GetHistorizeParams;

it('GetHistorizeParam Service returns the proper array', function () {
    $getHistorizeParams = new GetHistorizeParams();
    $historizeParams = $getHistorizeParams->getArray(app_path('Models/'.parent::getModelName() .'.php'));

    expect($historizeParams['TestHistorization'])->toBe('string');
    expect($historizeParams['AnotherHistorization'])->toBe('integer');
});
