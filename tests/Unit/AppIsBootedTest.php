<?php

namespace Tests\Unit;

it('config is set to testing', function () {
    expect(config('app.env'))->toBe('testing');
});
