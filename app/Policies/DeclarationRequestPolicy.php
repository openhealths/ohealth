<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Declaration\Status;
use App\Models\DeclarationRequest;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DeclarationRequestPolicy
{
    /**
     * Determine whether the user can view any declaration.
     */
    public function viewAny(User $user): Response
    {
        if ($user->cannot('declaration_request:read')) {
            return Response::denyWithStatus(404);
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can create declaration request.
     */
    public function create(User $user): Response
    {
        if ($user->cannot('declaration_request:write')) {
            return Response::denyWithStatus(404);
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can continue to create declaration request.
     */
    public function update(User $user, DeclarationRequest $declarationRequest): Response
    {
        if ($declarationRequest->legalEntityId !== legalEntity()->id) {
            return Response::denyWithStatus(404);
        }

        $user->load(['party:id,tax_id', 'party.employees:id,uuid,party_id']);
        // Check if belongs to employee_id
        if (!$user->party->employees->contains('id', $declarationRequest->employeeId)) {
            return Response::denyWithStatus(404);
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can create declaration request.
     */
    public function approve(User $user): Response
    {
        if ($user->cannot('declaration_request:approve')) {
            return Response::denyWithStatus(404);
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can reject declaration request.
     */
    public function reject(User $user): Response
    {
        if ($user->cannot('declaration_request:reject')) {
            return Response::denyWithStatus(404);
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can sign declaration request.
     */
    public function sign(User $user): Response
    {
        if ($user->cannot('declaration_request:sign')) {
            return Response::denyWithStatus(404);
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can delete draft declaration request.
     */
    public function delete(User $user, DeclarationRequest $declarationRequest): Response
    {
        if ($declarationRequest->legalEntityId !== legalEntity()->id) {
            return Response::denyWithStatus(404);
        }

        // Check if belongs to employee_id
        if (!$user->party->employees->contains('id', $declarationRequest->employeeId)) {
            return Response::denyWithStatus(404);
        }

        // Check if status is DRAFT
        if ($declarationRequest->status !== Status::DRAFT) {
            return Response::denyWithStatus(404);
        }

        return Response::allow();
    }
}
