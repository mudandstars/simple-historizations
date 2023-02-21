<?php

namespace Tests\Unit\Trait;

use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Mudandstars\HistorizeModelChanges\Models\DateHistorization;
use Mudandstars\HistorizeModelChanges\Models\IntegerHistorization;
use Mudandstars\HistorizeModelChanges\Models\TraitTestModel;

it('multiple historizations are created properly', function () {
    Artisan::call('make-historization-files');

    $model = TraitTestModel::create([
        'string' => 'test1',
        'date' => $previousDate = Carbon::yesterday(),
        'integer' => $previousInteger = 5,
    ]);

    expect(DateHistorization::count())->toEqual(0);

    $model->update([
        'date' => $newDate = Carbon::today(),
        'integer' => $newInteger = 10,
    ]);

    $dateHistorization = DateHistorization::where('previous_date', $previousDate)->first();
    $integerHistorization = IntegerHistorization::where('previous_integer', $previousInteger)->first();

    expect(DateHistorization::count())->toEqual(1);
    expect($dateHistorization->new_date)->toEqual($newDate);
    expect(IntegerHistorization::count())->toEqual(1);
    expect($integerHistorization->new_integer)->toEqual($newInteger);
});
