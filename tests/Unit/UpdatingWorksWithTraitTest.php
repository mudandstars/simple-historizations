<?php

namespace Tests\Unit;

use Mudandstars\HistorizeModelChanges\Models\TestModel;

it('model is updated properly', function () {
    $mockModel = TestModel::create([
        'string' => 'test1',
    ]);

    $mockModel->update([
        'string' => 'test2',
    ]);

    expect($mockModel->string)->toEqual('test2');
});
