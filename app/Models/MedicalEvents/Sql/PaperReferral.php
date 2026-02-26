<?php

declare(strict_types=1);

namespace App\Models\MedicalEvents\Sql;

use Eloquence\Behaviours\HasCamelCasing;
use Illuminate\Database\Eloquent\Model;

class PaperReferral extends Model
{
    use HasCamelCasing;

    protected $guarded = [];

    protected $hidden = [
        'id',
        'paper_referralable_type',
        'paper_referralable_id',
        'created_at',
        'updated_at'
    ];
}
