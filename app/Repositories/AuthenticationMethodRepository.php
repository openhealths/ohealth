<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Arr;
use App\Models\Person\Person;

class AuthenticationMethodRepository
{
    /**
     * Sync authentication methods data to the current state.
     * If $authMethods is empty, the existing data will be deleted.
     *
     * @param  Person  $person
     * @param  array  $authMethods
     * @return void
     */
    public function sync(Person $person, array $authMethods): void
    {
        $incomingUuids = collect($authMethods)->pluck('uuid')->filter()->values();

        // Delete unrelated (keep only the ones provided) and also delete methods without UUID
        $person->authenticationMethods()
            ->where(function ($query) use ($incomingUuids) {
                $query->whereNotIn('uuid', $incomingUuids)
                    ->orWhereNull('uuid');
            })
            ->delete();

        if (empty($authMethods)) {
            return;
        }

        // Update or create actual by uuid (unique identifier)
        foreach ($authMethods as $method) {
            $person->authenticationMethods()->updateOrCreate(
                ['uuid' => $method['uuid']],
                Arr::except($method, 'confidant_person')
            );
        }
    }
}
