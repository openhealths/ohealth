<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Person\ConfidantPersonRelationshipRequestStatus;
use App\Models\Relations\Document;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ConfidantPersonRelationshipRequest extends Model
{
    protected $table = 'confidant_person_relationship_requests';

    protected $hidden = [
        'id',
        'person_id',
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'uuid',
        'person_id',
        'action',
        'status',
        'channel',
        'documents'
    ];

    protected $casts = [
        'status' => ConfidantPersonRelationshipRequestStatus::class,
        'documents' => 'array'
    ];

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }
}
