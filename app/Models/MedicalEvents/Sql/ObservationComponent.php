<?php

declare(strict_types=1);

namespace App\Models\MedicalEvents\Sql;

use Eloquence\Behaviours\HasCamelCasing;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ObservationComponent extends Model
{
    use HasCamelCasing;

    protected $guarded = [];

    protected $hidden = [
        'id',
        'observation_id',
        'authority_id',
        'codeable_concept_id',
        'interpretation_id',
        'created_at',
        'updated_at'
    ];

    public function observation(): BelongsTo
    {
        return $this->belongsTo(Observation::class);
    }

    public function valueCodeableConcept(): BelongsTo
    {
        return $this->belongsTo(CodeableConcept::class, 'codeable_concept_id');
    }

    public function interpretation(): BelongsTo
    {
        return $this->belongsTo(CodeableConcept::class, 'interpretation_id');
    }
}
