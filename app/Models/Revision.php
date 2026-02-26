<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Employee\RevisionStatus;
use Illuminate\Database\Eloquent\Model;
use Eloquence\Behaviours\HasCamelCasing;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Revision extends Model
{
    use HasCamelCasing;
    use SoftDeletes;

    protected $hidden = [
        'id',
        'revisionable_type',
        'revisionable_id',
    ];

    protected $fillable = [
        'data',
        'ehealth_response',
        'status',
        'revisionable_type',
        'revisionable_id'
    ];

    protected $casts = [
        'data' => 'array',
        'deleted_at' => 'datetime',
        'status' => RevisionStatus::class,
        'ehealth_response' => 'array',
    ];

    public function revisionable(): MorphTo
    {
        return $this->morphTo();
    }

    public function setApplied(): void
    {
        $this->status = RevisionStatus::APPLIED;
        $this->save();
        $this->delete();
    }

    public function setOutdated(): void
    {
        $this->status = RevisionStatus::OUTDATED;
        $this->save();
        $this->delete();
    }
}
