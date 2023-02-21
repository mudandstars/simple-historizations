<?php

namespace Tests\Unit;

it('GetHistorizeParam Service returns the proper array', function () {
    $historizeParams = parent::getTestModelHistorizeParams();

    expect($historizeParams['TestHistorization'])->toBe('string');
    expect($historizeParams['IntegerHistorization'])->toBe('integer');
    expect($historizeParams['DateHistorization'])->toBe('date');
});
