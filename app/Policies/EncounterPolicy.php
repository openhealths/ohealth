<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class EncounterPolicy
{
    /**
     * Determine whether the user can view the encounter.
     */
    public function view(User $user): Response
    {
        if ($user->cannot('encounter:read')) {
            return Response::denyWithStatus(404);
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can create encounter.
     */
    public function create(User $user): Response
    {
        if ($user->cannot('encounter:write')) {
            return Response::denyWithStatus(404);
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can cancel encounter.
     */
    public function cancel(User $user): Response
    {
        if ($user->cannot('encounter:cancel')) {
            return Response::denyWithStatus(404);
        }

        return Response::allow();
    }
}
