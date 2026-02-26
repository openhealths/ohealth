<?php

declare(strict_types=1);

namespace App\Models\Contracts;

use App\Enums\Contract\Status;

class ContractRequest extends BaseContract
{
    protected $table = 'contract_requests';

    public function __construct(array $attributes = [])
    {
        $this->mergeFillable([
            'contractor_legal_entity_id',
            'contractor_owner_id',
            'contractor_employee_divisions',
            'contractor_signed',
            'nhs_legal_entity_id',
            'assignee_id',
            'previous_request_id',
            'parent_contract_id',
            'printout_content',
            'ehealth_inserted_by',
            'ehealth_inserted_at',
            'ehealth_updated_by',
            'ehealth_updated_at',
        ]);

        parent::__construct($attributes);
    }

    protected $casts = [
        'contractor_employee_divisions' => 'array',
        'status' => Status::class,
        'start_date' => 'date',
        'end_date' => 'date',
        'contractor_payment_details' => 'array',
        'contractor_divisions' => 'array',
        'data' => 'array',
        'medical_programs' => 'array',
    ];
}
