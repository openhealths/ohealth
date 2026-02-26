<?php

declare(strict_types=1);

namespace App\Models\MedicalEvents\Sql;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Quantity extends Model
{
    protected $guarded = [];

    protected $hidden = [
        'id',
        'quantityable_type',
        'quantityable_id',
        'created_at',
        'updated_at'
    ];

    public function quantityable(): MorphTo
    {
        return $this->morphTo();
    }
}
