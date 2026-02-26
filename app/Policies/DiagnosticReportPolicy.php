<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\MedicalEvents\Sql\DiagnosticReport;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DiagnosticReportPolicy
{
    /**
     * Determine whether the user can view the diagnostic report.
     */
    public function view(User $user, DiagnosticReport $diagnosticReport): Response
    {
        if ($user->cannot('diagnostic_report:read')) {
            return Response::denyWithStatus(404);
        }

        if ($diagnosticReport->managingOrganization->value !== legalEntity()->uuid) {
            return Response::denyWithStatus(404);
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can create diagnostic report.
     */
    public function create(User $user): Response
    {
        if ($user->cannot('diagnostic_report:write')) {
            return Response::denyWithStatus(404);
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can cancel diagnostic report.
     */
    public function cancel(User $user, DiagnosticReport $diagnosticReport): Response
    {
        if ($user->cannot('diagnostic_report:cancel')) {
            return Response::denyWithStatus(404);
        }

        if ($diagnosticReport->managingOrganization->value !== legalEntity()->uuid) {
            return Response::denyWithStatus(404);
        }

        return Response::allow();
    }
}
