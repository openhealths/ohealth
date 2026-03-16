<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Arr;
use App\Models\Relations\Party;
use App\Models\Employee\EmployeeRequest;

class PartyRepository
{
    /**
     * Find an existing party or create a new one based on the provided data.
     *
     * @param EmployeeRequest $model
     *
     * @param array $partyData
     *
     * @return Party|null
     */
    public function createPartyByEmployeeRequest(EmployeeRequest $model, array $partyData): ?Party
    {
        $documents = Arr::pull($partyData, 'documents', []);
        $phones = Arr::pull($partyData, 'phones', []);

        unset ($partyData['email']);

        $party = Party::where('tax_id', $partyData['tax_id'])
            ->where('birth_date', $partyData['birth_date'])
            ->where('first_name', $partyData['first_name'])
            ->where('last_name', $partyData['last_name'])
            ->where('second_name', $partyData['second_name'])
            ->orWhereNull('second_name')
            ->first();

        if (!empty($party)) {
            return $party;
        };

        $newParty = Party::create($partyData);

        $model->party()->associate($newParty)->save();

        $model->party->syncMany('documents', $documents);
        $model->party->syncMany('phones', $phones);

        return $newParty;
    }
}
