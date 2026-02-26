<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\EhealthUserVerified;
use Spatie\Permission\Models\Role;

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

        setPermissionsTeamId($legalEntityId);
        $user->unsetRelation('roles');

        $roleNames = $user->party->employees()
            ->where('legal_entity_id', $legalEntityId)
            ->where('status', 'APPROVED')
            ->pluck('employee_type')
            ->unique()
            ->toArray();

        if (empty($roleNames)) {
            $user->syncRoles([]);

            return;
        }

        $allRoles = Role::whereIn('name', $roleNames)->get();

        if ($allRoles->isEmpty()) {
            return;
        }

        $user->syncRoles($allRoles);
    }
}
