<?php

namespace Mudandstars\HistorizeModelChanges\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DateHistorization extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    protected $dates = [
        'created_at',
        'previous_date',
        'new_date',
    ];

    public function traitTestModels(): BelongsTo
    {
        return $this->belongsTo(TraitTestModel::class);
    }
}
