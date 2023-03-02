<?php

namespace Mudandstars\HistorizeModelChanges\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IntegerHistorization extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function traitTestModels(): BelongsTo
    {
        return $this->belongsTo(TraitTestModel::class);
    }
}
