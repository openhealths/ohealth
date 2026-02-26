<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Status;
use App\Models\Employee\Employee;
use Eloquence\Behaviours\HasCamelCasing;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeRole extends Model
{
    use HasCamelCasing;

    protected $fillable = [
        'uuid',
        'employee_id',
        'healthcare_service_id',
        'start_date',
        'end_date',
        'status',
        'is_active',
        'ehealth_inserted_at',
        'ehealth_inserted_by',
        'ehealth_updated_at',
        'ehealth_updated_by'
    ];

    protected $hidden = ['id'];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'status' => Status::class
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function healthcareService(): BelongsTo
    {
        return $this->belongsTo(HealthcareService::class);
    }

    /**
     * List of employee roles for current legal entity.
     *
     * @param  Builder  $query
     * @return Builder
     */
    #[Scope]
    protected function forLegalEntity(Builder $query): Builder
    {
        return $query->with([
            'employee:id,party_id',
            'employee.party:id,first_name,last_name,second_name',
            'healthcareService:id,legal_entity_id,division_id,speciality_type,providing_condition',
            'healthcareService.legalEntity:id',
            'healthcareService.division:id,name'
        ])
            ->select(['id', 'uuid', 'employee_id', 'healthcare_service_id', 'start_date', 'end_date', 'status', 'is_active'])
            ->whereHas(
                'healthcareService',
                fn (Builder $query) => $query->select('id')->where('legal_entity_id', legalEntity()->id)
            )
            ->orderByDesc('created_at');
    }

    /**
     * Filter by party full name.
     *
     * @param  Builder  $query
     * @param  string  $search
     * @return Builder
     */
    #[Scope]
    protected function filterByEmployeeSearch(Builder $query, string $search): Builder
    {
        if ($search) {
            $query->whereHas(
                'employee',
                fn (Builder $employeeQuery) => $employeeQuery->select('id')
                    ->whereHas(
                        'party',
                        fn (Builder $partyQuery) => $partyQuery->select('id')
                            ->where('first_name', 'ILIKE', "%$search%")
                            ->orWhere('last_name', 'ILIKE', "%$search%")
                            ->orWhere('second_name', 'ILIKE', "%$search%")
                    )
            );
        }

        return $query;
    }

    #[Scope]
    protected function filterBySpecialityType(Builder $query, ?string $specialityTypeFilter): Builder
    {
        if ($specialityTypeFilter) {
            $query->whereHas('healthcareService', fn (Builder $subQuery) => $subQuery->select('speciality_type')
                ->where('speciality_type', $specialityTypeFilter));
        }

        return $query;
    }

    #[Scope]
    protected function filterByStatus(Builder $query, array $status): Builder
    {
        if ($status) {
            $query->whereIn('status', $status);
        }

        return $query;
    }
}
