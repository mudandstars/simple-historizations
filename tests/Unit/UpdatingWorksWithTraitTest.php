<?php

namespace Tests\Unit;

use Mudandstars\HistorizeModelChanges\Models\TestModel;

it('model is updated properly', function () {
   $mockModel = TestModel::create([
    'test' => 'test1'
   ]);

   $mockModel->update([
    'test' => 'test2'
   ]);

   expect($mockModel->test)->toEqual('test2');
});
