<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\User\Role;
use App\Models\Declaration;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DeclarationPolicy
{
    /**
     * Determine whether the user can view any declaration.
     */
    public function viewAny(User $user): Response
    {
        if ($user->cannot('declaration:read')) {
            return Response::denyWithStatus(404);
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can view declaration.
     */
    public function view(User $user, Declaration $declaration): Response
    {
        if ($user->cannot('declaration:read')) {
            return Response::denyWithStatus(404);
        }

        if ($user->hasRole(Role::OWNER) && $declaration->legalEntityId === legalEntity()->id) {
            return Response::allow();
        }

        // Сan only view their own
        return $user->party->employees()->whereKey($declaration->employeeId)->exists()
            ? Response::allow()
            : Response::denyWithStatus(404);
    }

    /**
     * Determine whether the user can synchronize all the declarations.
     */
    public function sync(User $user): Response
    {
        if ($user->cannot('declaration:read') || $user->cannot('declaration_request:read') || $user->cannot('person:read')) {
            return Response::denyWithStatus(404);
        }

        return Response::allow();
    }
}
