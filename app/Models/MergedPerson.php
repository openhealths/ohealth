<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Person\MergedPersonStatus;
use App\Models\Person\Person;
use Eloquence\Behaviours\HasCamelCasing;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MergedPerson extends Model
{
    use HasCamelCasing;

    protected $table = 'merged_persons';

    protected $fillable = [
        'uuid',
        'person_id',
        'merged_uuid',
        'merged_person_id',
        'merged_preperson_id',
        'status',
        'ehealth_inserted_at',
        'ehealth_updated_at'
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at'
    ];

    protected $casts = ['status' => MergedPersonStatus::class];

    /**
     * The identified patient the person was merged into.
     *
     * @return BelongsTo
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * The identified person that was merged into the patient, when their record is known locally.
     *
     * @return BelongsTo
     */
    public function mergedPerson(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'merged_person_id');
    }

    /**
     * The preperson that was merged into the patient, when their record is known locally.
     *
     * @return BelongsTo
     */
    public function mergedPreperson(): BelongsTo
    {
        return $this->belongsTo(Preperson::class, 'merged_preperson_id');
    }
}
