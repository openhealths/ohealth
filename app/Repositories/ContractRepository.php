<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Classes\eHealth\Api\Contract as ContractMapper; // ФІКС: Правильний мапер
use App\Models\Contracts\Contract;

class ContractRepository
{
    /**
     * Saves or updates a contract based on data received from E-Health API.
     */
    public function saveFromEHealth(array $eHealthData): Contract
    {
        // 1. Using the right mapper for Contracts
        $mapper = app(ContractMapper::class);
        $attributes = $mapper->mapCreate($eHealthData);

        // 2. API returns 'id' and in the database it is 'uuid'
        if (isset($eHealthData['id'])) {
            $attributes['uuid'] = $eHealthData['id'];
            unset($attributes['id']); // Прибираємо, щоб не плутати з внутрішнім id
        }

        // 3. Adding local context
        $attributes['legal_entity_id'] = legalEntity()->id;

        // 4. Additional fields that may not pass through the mapper
        if (isset($eHealthData['contract_number'])) {
            $attributes['contract_number'] = $eHealthData['contract_number'];
        }

        // Save raw data for display (if necessary)
        // $attributes['data'] = $eHealthData;

        // 5.Using updateOrCreate by UUID
        //This does NOT cause the "invalid input syntax" error, because we search for the uuid column
        return Contract::updateOrCreate(
            ['uuid' => $attributes['uuid']],
            $attributes
        );
    }
}
