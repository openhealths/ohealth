<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\User;
use Illuminate\Support\Carbon;
use App\Events\EhealthUserVerified;
use App\Services\UserRoleSyncService;

class SyncUserRolesAfterVerification
{
    /**
     * Synchronizes a user's roles based on their employee positions after
     * their identity has been successfully verified and linked to a Party.
     *
     * This listener is triggered by the UserVerifiedAndLinked event, ensuring that
     * the user receives the complete and correct set of roles corresponding to all
     * their official positions within a specific legal entity.
     */
    public function handle(EhealthUserVerified $event): void
    {
        $user = $event->user;
        $legalEntityId = $event->legalEntityId;

        $user->party->syncAvailableEmployeesAndUsers();
        $user->party->syncAvailableRolesAndUsers($legalEntityId);
    }

    /**
     * Get roles based on employee types associated with the user's party.
     *
     * Only includes employee types where the user was created before or at the same time
     * as the employee record was inserted, ensuring proper role assignment chronology.
     *
     * @param  User  $user
     *
     * @return array<string> Array of unique employee type role identifiers.
     */
    protected function getAvailableRolesForUser(User $user): array
    {
        $roles = [];
        $user = $user->loadMissing('party');
        $userCreatedTime = Carbon::parse($user->inserted_at);

        // Get the earliest inserted_at time among the user's employees to ensure we consider the correct employee records for role assignment
        $userFirstEmployeeCreatedTime = $user->party?->employees()->orderBy('inserted_at')->first()?->inserted_at;

        $userCreatedTime = $userCreatedTime->min($userFirstEmployeeCreatedTime);

        $partyEmployees = $user->party?->employees ?? [];

        foreach ($partyEmployees as $employee) {
            if ($employee->employeeType && $employee->insertedAt && $userCreatedTime && $userCreatedTime->lessThanOrEqualTo($employee->insertedAt)) {
                $roles[] = $employee->employeeType;
            }
        }

        return array_unique($roles);
    }
}
