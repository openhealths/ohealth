<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Declaration\Status;
use App\Enums\JobStatus;
use App\Models\Employee\Employee;
use App\Models\Person\Person;
use Eloquence\Behaviours\HasCamelCasing;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

class DeclarationRequest extends Model
{
    use HasCamelCasing;

    protected $guarded = [];

    protected $casts = [
        'status' => Status::class,
        'data_to_be_signed' => 'array'
    ];

    #[Scope]
    protected function filterByLegalEntityId(Builder $query, int $legalEntityId): Builder
    {
        return $query->whereLegalEntityId($legalEntityId);
    }

    #[Scope]
    protected function filterBySyncStatus(Builder $query, JobStatus $status): Builder
    {
        return $query->whereSyncStatus($status);
    }

    #[Scope]
    protected function forEmployees(Builder $query, array $employeeIds): Builder
    {
        return $query->whereIn('employee_id', $employeeIds);
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }
}
