<?php

namespace Mudandstars\SimpleHistorizations\Models;

use Illuminate\Database\Eloquent\Model;
use Mudandstars\SimpleHistorizations\Traits\HistorizeModelChange;

class TraitTestModel extends Model
{
    use HistorizeModelChange;

    protected $guarded = [];

    protected $historize = [
        'DateHistorization' => 'date',
        'IntegerHistorization' => 'integer',
    ];
}
