<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Equipment\Type;
use Illuminate\Database\Eloquent\Model;

class EquipmentName extends Model
{
    protected $fillable = [
        'equipment_id',
        'name',
        'type'
    ];

    protected $hidden = ['id', 'equipment_id', 'created_at', 'updated_at'];

    protected $casts = ['type' => Type::class];
}
