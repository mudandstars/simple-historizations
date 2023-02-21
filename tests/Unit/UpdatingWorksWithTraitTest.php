<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Artisan;
use Mudandstars\HistorizeModelChanges\Models\TraitTestModel;

it('model is updated properly', function () {
    Artisan::call('make-historization-files');

    $mockModel = TraitTestModel::create([
        'string' => 'test1',
    ]);

    $mockModel->update([
        'string' => 'test2',
    ]);

    expect($mockModel->string)->toEqual('test2');
});
