<?php

namespace Tests\Unit;

use Mudandstars\HistorizeModelChanges\Models\TraitTestModel;

it('model is updated properly', function () {
    $mockModel = TraitTestModel::create([
        'string' => 'test1',
    ]);

    $mockModel->update([
        'string' => 'test2',
    ]);

    expect($mockModel->string)->toEqual('test2');
});
