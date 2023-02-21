<?php

namespace Mudandstars\HistorizeModelChanges\Models;

use Illuminate\Database\Eloquent\Model;
use Mudandstars\HistorizeModelChanges\Traits\HistorizeModelChange;

class TraitTestModel extends Model
{
    use HistorizeModelChange;

    protected $guarded = [];

    protected $historize = [
        'DateHistorization' => 'date',
        'IntegerHistorization' => 'integer',
    ];
}
