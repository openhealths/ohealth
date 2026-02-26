<?php

declare(strict_types=1);

namespace App\Models\MedicalEvents\Sql;

use Carbon\CarbonImmutable;
use Eloquence\Behaviours\HasCamelCasing;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class DiagnosticReport extends Model
{
    use HasCamelCasing;

    protected $guarded = [];

    protected $hidden = [
        'id',
        'encounter_internal_id',
        'based_on_id',
        'code_id',
        'issued',
        'conclusion_code_id',
        'recorded_by_id',
        'division_id',
        'managing_organization_id',
        'encounter_id',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'issued' => 'immutable_datetime',
    ];

    protected $appends = [
        'issued_date',
        'issued_time',
        'effective_period_start_date',
        'effective_period_start_time',
        'effective_period_end_date',
        'effective_period_end_time'
    ];

    protected function issuedDate(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->issued->toDateString()
        );
    }

    protected function issuedTime(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->issued->toTimeString()
        );
    }

    protected function effectivePeriodStartDate(): Attribute
    {
        return Attribute::make(
            get: fn () => isset($this->effectivePeriod['start'])
                ? CarbonImmutable::parse($this->effectivePeriod['start'])->toDateString()
                : null
        );
    }

    protected function effectivePeriodStartTime(): Attribute
    {
        return Attribute::make(
            get: fn () => isset($this->effectivePeriod['start'])
                ? CarbonImmutable::parse($this->effectivePeriod['start'])->toTimeString()
                : null
        );
    }

    protected function effectivePeriodEndDate(): Attribute
    {
        return Attribute::make(
            get: fn () => isset($this->effectivePeriod['end'])
                ? CarbonImmutable::parse($this->effectivePeriod['end'])->toDateString()
                : null
        );
    }

    protected function effectivePeriodEndTime(): Attribute
    {
        return Attribute::make(
            get: fn () => isset($this->effectivePeriod['end'])
                ? CarbonImmutable::parse($this->effectivePeriod['end'])->toTimeString()
                : null
        );
    }

    public function basedOn(): BelongsTo
    {
        return $this->belongsTo(Identifier::class, 'based_on_id');
    }

    public function paperReferral(): MorphOne
    {
        return $this->morphOne(PaperReferral::class, 'paper_referralable');
    }

    public function code(): BelongsTo
    {
        return $this->belongsTo(Identifier::class, 'code_id');
    }

    public function category(): BelongsToMany
    {
        return $this->belongsToMany(CodeableConcept::class, 'diagnostic_report_categories')->withTimestamps();
    }

    public function effectivePeriod(): MorphOne
    {
        return $this->morphOne(Period::class, 'periodable');
    }

    public function conclusionCode(): BelongsTo
    {
        return $this->belongsTo(CodeableConcept::class, 'conclusion_code_id');
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(Identifier::class, 'recorded_by_id');
    }

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Identifier::class, 'encounter_id');
    }

    public function performer(): HasOne
    {
        return $this->hasOne(DiagnosticReportPerformer::class);
    }

    public function managingOrganization(): BelongsTo
    {
        return $this->belongsTo(Identifier::class, 'managing_organization_id');
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Identifier::class, 'division_id');
    }

    public function reportOrigin(): BelongsTo
    {
        return $this->belongsTo(CodeableConcept::class, 'report_origin_id');
    }

    public function resultsInterpreter(): HasOne
    {
        return $this->hasOne(DiagnosticReportResultsInterpreter::class);
    }
}
