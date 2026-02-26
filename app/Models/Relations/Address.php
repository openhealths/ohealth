<?php

declare(strict_types=1);

namespace App\Models\Relations;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Address extends Model
{
    public const string DEFAULT_TYPE = 'RESIDENCE';

    public const string RECEPTION_TYPE = 'RECEPTION';

    public const string DEFAULT_COUNTRY = 'UA';

    protected $hidden = [
        'id',
        'addressable_id',
        'addressable_type',
        'settlement_type',
        'settlement_id',
        'street_type',
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'type',
        'country',
        'area',
        'region',
        'settlement',
        'settlement_type',
        'settlement_id',
        'street_type',
        'street',
        'building',
        'apartment',
        'zip',
        'addressable_id',
        'addressable_type',
    ];

    protected $appends = [
        'settlementId',
        'settlementType',
        'streetType',
    ];

    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getSettlementIdAttribute()
    {
        return $this->attributes['settlement_id'] ?? null;
    }

    public function getSettlementTypeAttribute()
    {
        return $this->attributes['settlement_type'] ?? null;
    }

    public function getStreetTypeAttribute()
    {
        return $this->attributes['street_type'] ?? null;
    }

    public function fill(array $attributes)
    {
        $convertedAttributes = [];

        foreach ($attributes as $key => $value) {
            if (!in_array($key, $this->fillable)) {
                $key = Str::snake($key);
            }

            $convertedAttributes[$key] = $value;
        }

        return parent::fill($convertedAttributes);
    }
}
