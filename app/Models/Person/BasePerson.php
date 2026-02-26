<?php

declare(strict_types=1);

namespace App\Models\Person;

use App\Casts\EHealthDateCast;
use App\Models\Relations\Address;
use App\Models\Relations\AuthenticationMethod;
use App\Models\Relations\Document;
use App\Models\Relations\Phone;
use Carbon\CarbonImmutable;
use Eloquence\Behaviours\HasCamelCasing;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

abstract class BasePerson extends Model
{
    use HasCamelCasing;

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'uuid',
        'first_name',
        'last_name',
        'second_name',
        'birth_date',
        'birth_country',
        'birth_settlement',
        'gender',
        'email',
        'no_tax_id',
        'tax_id',
        'secret',
        'unzr',
        'emergency_contact',
        'patient_signed',
        'process_disclosure_data_consent'
    ];

    protected $casts = [
        'emergency_contact' => 'array',
        'birth_date' => EHealthDateCast::class
    ];

    /**
     * Get the person's full name.
     *
     * @return Attribute
     */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => trim($this->last_name . ' ' . $this->first_name . ' ' . $this->second_name)
        );
    }

    protected function age(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->birth_date ? CarbonImmutable::parse($this->birth_date)->age : null
        );
    }

    public function addresses(): MorphMany
    {
        return $this->MorphMany(Address::class, 'addressable');
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function phones(): MorphMany
    {
        return $this->morphMany(Phone::class, 'phoneable');
    }

    public function authenticationMethods(): MorphMany
    {
        return $this->morphMany(AuthenticationMethod::class, 'authenticatable');
    }
}
