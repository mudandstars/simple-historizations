<?php

namespace Mudandstars\HistorizeModelChanges\Models;

use Illuminate\Database\Eloquent\Model;
use Mudandstars\HistorizeModelChanges\Traits\HistorizeModelChange;

class MockModel extends Model
{
    use HistorizeModelChange;
}
