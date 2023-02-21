<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Mudandstars\HistorizeModelChanges\Models\TraitTestModel;
use Mudandstars\HistorizeModelChanges\Models\DateHistorization;
use Mudandstars\HistorizeModelChanges\Models\IntegerHistorization;

it('historization is created if column-value is included in the update-attributes array', function () {
    Artisan::call('make-historization-files');

    $model = TraitTestModel::create([
        'string' => 'test1',
        'date' => Carbon::yesterday(),
    ]);

    expect(DateHistorization::count())->toEqual(0);

    $model->update([
        'date' => Carbon::today(),
    ]);

    $historizationModel = DateHistorization::where('previous_date', Carbon::yesterday())->first();

    expect(DateHistorization::count())->toEqual(1);
    expect($historizationModel->new_date)->toEqual(Carbon::today());
});

it('historization is not created if column gets assigned same value', function () {
    Artisan::call('make-historization-files');

    $date = Carbon::yesterday();

    $model = TraitTestModel::create([
        'string' => 'test1',
        'date' => $date,
    ]);

    expect(DateHistorization::count())->toEqual(0);

    $model->update([
        'date' => $date,
    ]);

    expect(DateHistorization::count())->toEqual(0);
});

it('historization is not created if it is not in the update-attributes array', function () {
    Artisan::call('make-historization-files');

    $date = Carbon::yesterday();

    $model = TraitTestModel::create([
        'string' => 'test1',
        'date' => $date,
    ]);

    expect(DateHistorization::count())->toEqual(0);

    $model->update([
        'string' => 'test2',
    ]);

    expect(DateHistorization::count())->toEqual(0);
});

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
