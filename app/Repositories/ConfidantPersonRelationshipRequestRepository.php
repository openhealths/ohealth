<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ConfidantPersonRelationshipRequest;
use App\Models\Person\Person;

class ConfidantPersonRelationshipRequestRepository
{
    /**
     * Sync confidant person relationship requests data to the current state.
     * If $requests is empty, the existing data will be deleted.
     *
     * @param  Person  $person
     * @param  array  $requests
     * @return void
     */
    public function sync(Person $person, array $requests): void
    {
        if (empty($requests)) {
            // Remove all existing requests if no data provided
            $person->confidantPersonRelationshipRequests()->delete();

            return;
        }

        foreach ($requests as $requestData) {
            ConfidantPersonRelationshipRequest::updateOrCreate(
                [
                'person_id' => $person->id,
                'uuid' => $requestData['uuid']
            ],
                $requestData
            );
        }
    }
}
