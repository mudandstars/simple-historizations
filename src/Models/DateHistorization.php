<?php

namespace Mudandstars\SimpleHistorizations\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DateHistorization extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
        'previous_date' => 'datetime',
        'new_date' => 'datetime',
    ];

    public function traitTestModels(): BelongsTo
    {
        return $this->belongsTo(TraitTestModel::class);
    }
}
