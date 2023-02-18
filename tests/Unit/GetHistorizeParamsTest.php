<?php

namespace Tests\Unit;

it('GetHistorizeParam Service returns the proper array', function () {
    $historizeParams = parent::getTestModelHistorizeParams();

    expect($historizeParams['TestHistorization'])->toBe('string');
    expect($historizeParams['AnotherHistorization'])->toBe('integer');
});
