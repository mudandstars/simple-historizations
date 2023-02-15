<?php

namespace Mudandstars\HistorizeModelChanges\Models;

use Illuminate\Database\Eloquent\Model;
use Mudandstars\HistorizeModelChanges\Traits\HistorizeModelChange;

class TestModel extends Model
{
    use HistorizeModelChange;

    protected $guarded = [];
}
