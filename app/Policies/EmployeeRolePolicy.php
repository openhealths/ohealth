<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Status;
use App\Models\EmployeeRole;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EmployeeRolePolicy
{
    /**
     * User allowed to view the list of healthcare services
     */
    public function viewAny(User $user): Response
    {
        if ($user->cannot('employee_role:read')) {
            return Response::denyWithStatus(404);
        }

        return Response::allow();
    }

    /**
     * User allow to add an employee role
     */
    public function create(User $user): Response
    {
        if ($user->cannot('employee_role:write')) {
            return Response::denyWithStatus(404);
        }

        // Can be created for legal entities with the following statuses.
        if (!in_array(legalEntity()->status, ['ACTIVE', 'SUSPENDED'], true)) {
            return Response::denyWithStatus(404);
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can deactivate employee role.
     */
    public function deactivate(User $user, EmployeeRole $employeeRole): Response
    {
        if ($user->cannot('employee_role:write')) {
            return Response::denyWithStatus(404);
        }

        // Legal entity can deactivate only its own employee roles
        if ($employeeRole->healthcareService->legalEntity->id !== legalEntity()->id) {
            return Response::denyWithStatus(404);
        }

        // Can be created for legal entities with the following statuses.
        if (!in_array(legalEntity()->status, ['ACTIVE', 'SUSPENDED'], true)) {
            return Response::denyWithStatus(404);
        }

        // Check that employee role with such ID exists in the system (is_active = true)
        if (!$employeeRole->isActive) {
            return Response::denyWithStatus(404);
        }

        // Only ACTIVE employee role can be deactivated
        if ($employeeRole->status !== Status::ACTIVE) {
            return Response::denyWithStatus(404);
        }

        return Response::allow();
    }
}
