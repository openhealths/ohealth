<?php

declare(strict_types=1);

namespace App\Models\Relations;

use Illuminate\Database\Eloquent\Model;
use Eloquence\Behaviours\HasCamelCasing;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Phone extends Model
{
    use HasCamelCasing;

    public const string TYPE_MOBILE = 'MOBILE';
    public const string TYPE_LAND_LINE = 'LAND_LINE';

    protected $hidden = [
        'id',
        'phoneable_type',
        'phoneable_id',
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'type',
        'number',
        'phoneable_type',
        'phoneable_id'
    ];

    /**
     * Returns an array of available phone types.
     *
     * @return array
     */
    public static function getPhoneTypes(): array
    {
        return [
            self::TYPE_MOBILE,
            self::TYPE_LAND_LINE
        ];
    }

    public function phoneable(): MorphTo
    {
        return $this->morphTo();
    }
}
