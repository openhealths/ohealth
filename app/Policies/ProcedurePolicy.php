<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\MedicalEvents\Sql\Procedure;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProcedurePolicy
{
    /**
     * Determine whether the user can view the procedure.
     */
    public function view(User $user, Procedure $procedure): Response
    {
        if ($user->cannot('procedure:read')) {
            return Response::denyWithStatus(404);
        }

        if ($procedure->managingOrganization->value !== legalEntity()->uuid) {
            return Response::denyWithStatus(404);
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can create procedure.
     */
    public function create(User $user): Response
    {
        if ($user->cannot('procedure:write')) {
            return Response::denyWithStatus(404);
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can cancel procedure.
     */
    public function cancel(User $user, Procedure $procedure): Response
    {
        if ($user->cannot('procedure:cancel')) {
            return Response::denyWithStatus(404);
        }

        if ($procedure->managingOrganization->value !== legalEntity()->uuid) {
            return Response::denyWithStatus(404);
        }

        return Response::allow();
    }
}
