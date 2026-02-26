<?php

declare(strict_types=1);

namespace App\Models\Relations;

use App\Casts\EHealthDateCast;
use Eloquence\Behaviours\HasCamelCasing;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ScienceDegree extends Model
{
    use HasCamelCasing;

    protected $hidden = [
        'id',
        'science_degreeable_id',
        'science_degreeable_type',
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'country',
        'city',
        'institution_name',
        'issued_date',
        'degree',
        'diploma_number',
        'speciality',
    ];

    protected $casts = [
        'issued_date' => EHealthDateCast::class,
    ];

    public function science_degreeable(): MorphTo
    {
        return $this->morphTo();
    }
}
