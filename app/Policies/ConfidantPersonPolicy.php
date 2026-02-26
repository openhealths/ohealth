<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class ConfidantPersonPolicy
{
    /**
     * Determine whether the user can create confidant person relationship request.
     */
    public function create(User $user): Response
    {
        if ($user->cannot('confidant_person_relationship_request:write')) {
            return Response::denyWithStatus(404);
        }

        return Response::allow();
    }
}
