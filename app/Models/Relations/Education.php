<?php

declare(strict_types=1);

namespace App\Models\Relations;

use App\Casts\EHealthDateCast;
use Eloquence\Behaviours\HasCamelCasing;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Education extends Model
{
    use HasCamelCasing;

    protected $hidden = [
        'id',
        'educationable_id',
        'educationable_type',
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'country',
        'city',
        'institution_name',
        'issued_date',
        'diploma_number',
        'degree',
        'speciality',
    ];

    protected $casts = [
        'issued_date' => EHealthDateCast::class,
    ];

    protected $table = 'educations';//TODO: Перевірити чому laravel підтягую назву таблиці education

    public function educationable(): MorphTo
    {
        return $this->morphTo();
    }
}
