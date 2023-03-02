<?php

namespace Mudandstars\SimpleHistorizations\Models;

use Illuminate\Database\Eloquent\Model;
use Mudandstars\SimpleHistorizations\Traits\SimpleHistorizations;

class TraitTestModel extends Model
{
    use SimpleHistorizations;

    protected $guarded = [];

    protected $historize = [
        'DateHistorization' => 'date',
        'IntegerHistorization' => 'integer',
    ];
}
