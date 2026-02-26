<?php

declare(strict_types=1);

namespace App\Livewire\Encounter\Forms\Api;

class EncounterRequestApi
{
    /**
     * Build an array of parameters for a service request list.
     *
     * @param  string  $requisition  A shared identifier common to all service requests that were authorized more or less simultaneously by a single author, representing the composite or group identifier. Example: AX654-654T.
     * @param  string  $status  The status of the service request. Default: active.
     * @param  int  $page  Page number. Default: 1.
     * @param  int  $pageSize  A limit on the number of objects to be returned, between 1 and 100. Default: 50.
     * @return array
     */
    public static function buildGetServiceRequestList(
        string $requisition,
        string $status = 'active',
        int $page = 1,
        int $pageSize = 50
    ): array {
        return [
            'requisition' => $requisition,
            'status' => $status,
            'page' => $page,
            'page_size' => $pageSize
        ];
    }

    /**
     * Build an array of parameters for a service request list.
     *
     * @param  int  $page  Page number. Default: 1.
     * @param  int  $pageSize  A limit on the number of objects to be returned, between 1 and 100. Default: 50.
     * @param  string|null  $code  Current diagnosis code. Example: R80.
     * @return array
     */
    public static function buildGetApprovedEpisodes(int $page = 1, int $pageSize = 50, ?string $code = null): array
    {
        return [
            'page' => $page,
            'page_size' => $pageSize,
            'code' => $code
        ];
    }

    /**
     * Build an array of parameters for a conditions list.
     *
     * @param  int  $page
     * @param  int  $pageSize
     * @param  string|null  $code
     * @param  string|null  $encounterId
     * @param  string|null  $episodeId
     * @param  string|null  $onsetDateFrom
     * @param  string|null  $onsetDateTo
     * @param  string|null  $managingOrganizationId
     * @return array
     */
    public static function buildGetConditions(
        int $page = 1,
        int $pageSize = 50,
        ?string $code = null,
        ?string $encounterId = null,
        ?string $episodeId = null,
        ?string $onsetDateFrom = null,
        ?string $onsetDateTo = null,
        ?string $managingOrganizationId = null
    ): array {
        return [
            'page' => $page,
            'page_size' => $pageSize,
            'code' => $code,
            'encounter_id' => $encounterId,
            'episode_id' => $episodeId,
            'onset_date_from' => $onsetDateFrom,
            'onset_date_to' => $onsetDateTo,
            'managing_organization_id' => $managingOrganizationId
        ];
    }

    /**
     * Build an array of parameters for a conditions list in episode context.
     *
     * @param  string  $patientUuid  Patient identifier Example: 70a9e15b-b71b-4caf-8f2e-ff247e8a5677.
     * @param  string  $episodeUuid  Episode identifier Example: a10aeafb-0df2-4091-bc83-f07e92a100ae.
     * @param  string|null  $code  Example: A20.
     * @param  string|null  $onsetDateFrom  Example: 1990-01-01.
     * @param  string|null  $onsetDateTo  Example: 2000-01-01.
     * @param  int  $page  Page number. Default: 1. Example: 2.
     * @param  int  $pageSize  A limit on the number of objects to be returned, between 1 and 100. Default: 50. Example: 50.
     * @return array
     */
    public static function buildGetConditionsInEpisodeContext(
        string $patientUuid,
        string $episodeUuid,
        ?string $code = null,
        ?string $onsetDateFrom = null,
        ?string $onsetDateTo = null,
        int $page = 1,
        int $pageSize = 50
    ): array {
        return [
            'patient_id' => $patientUuid,
            'episode_id' => $episodeUuid,
            'code' => $code,
            'onset_date_from' => $onsetDateFrom,
            'onset_date_to' => $onsetDateTo,
            'page' => $page,
            'page_size' => $pageSize
        ];
    }

    /**
     * Build an array of parameters for an observations list in episode context.
     *
     * @param  string  $patientUuid  Patient identifier Example: 70a9e15b-b71b-4caf-8f2e-ff247e8a5677.
     * @param  string  $episodeUuid  Episode identifier Example: a10aeafb-0df2-4091-bc83-f07e92a100ae.
     * @param  string|null  $code  Example: 10569-2.
     * @param  string|null  $issuedFrom  Example: 1990-01-01.
     * @param  string|null  $issuedTo  Example: 2000-01-01.
     * @param  int  $page  Page number. Default: 1. Example: 2.
     * @param  int  $pageSize  A limit on the number of objects to be returned, between 1 and 100. Default: 50. Example: 50.
     * @return array
     */
    public static function buildGetObservationsInEpisodeContext(
        string $patientUuid,
        string $episodeUuid,
        ?string $code = null,
        ?string $issuedFrom = null,
        ?string $issuedTo = null,
        int $page = 1,
        int $pageSize = 50
    ): array {
        return [
            'patient_id' => $patientUuid,
            'episode_id' => $episodeUuid,
            'code' => $code,
            'issued_from' => $issuedFrom,
            'issued_to' => $issuedTo,
            'page' => $page,
            'page_size' => $pageSize
        ];
    }

    /**
     * Build an array of parameters for getting episodes using a search parameters list.
     *
     * @param  string|null  $periodFrom  Example: 2017-01-01.
     * @param  string|null  $periodTo  Example: 2018-01-01.
     * @param  string|null  $code  Example: R80.
     * @param  string|null  $status  Example: active.
     * @param  string|null  $managingOrganizationId  Example: 80a9e15b-b71b-4caf-8f2e-ff247e8a5677.
     * @param  int|null  $page  Page number. Default: 1.
     * @param  int|null  $pageSize  A limit on the number of objects to be returned, between 1 and 100. Default: 50.
     * @return array
     */
    public static function buildGetEpisodeBySearchParams(
        ?string $periodFrom = null,
        ?string $periodTo = null,
        ?string $code = null,
        ?string $status = null,
        ?string $managingOrganizationId = null,
        ?int $page = 1,
        ?int $pageSize = 50
    ): array {
        return [
            'period_from' => $periodFrom,
            'period_to' => $periodTo,
            'code' => $code,
            'status' => $status,
            'managing_organization_id' => $managingOrganizationId,
            'page' => $page,
            'page_size' => $pageSize
        ];
    }

    /**
     * Build an array of parameters for getting clinical impressions using a search parameters list.
     *
     * @param  string  $patientUuid  MPI identifier of the patient. Example: 7c3da506-804d-4550-8993-bf17f9ee0402
     * @param  string|null  $encounterUuid  Identifier of the encounter in clinical impression. Example: 7c3da506-804d-4550-8993-bf17f9ee0400
     * @param  string|null  $episodeUuid  Example: f48d1b6c-a021-4d6a-a5a4-aee93e152ecc
     * @param  string|null  $code  Clinical impression's code. Example: insulin_1
     * @param  string|null  $status  Clinical impression's status. Example: completed
     * @param  string|null  $effectiveDateTo  Date of clinical impression. Example: 2017-09-01
     * @param  string|null  $effectiveDateFrom  Date of clinical impression. Example: 2017-09-02
     * @param  int  $page  Page number. Default: 1
     * @param  int  $pageSize  A limit on the number of objects to be returned, between 1 and 100. Default: 50.
     * @return array
     */
    public static function buildGetClinicalImpressionBySearchParams(
        string $patientUuid,
        ?string $encounterUuid = null,
        ?string $episodeUuid = null,
        ?string $code = null,
        ?string $status = null,
        ?string $effectiveDateTo = null,
        ?string $effectiveDateFrom = null,
        int $page = 1,
        int $pageSize = 50
    ): array {
        return [
            'patient_id' => $patientUuid,
            'encounter_id' => $encounterUuid,
            'episode_id' => $episodeUuid,
            'code' => $code,
            'status' => $status,
            'effective_date_to' => $effectiveDateTo,
            'effective_date_from' => $effectiveDateFrom,
            'page' => $page,
            'page_size' => $pageSize
        ];
    }

    /**
     * Build an array of parameters for getting encounters using a search parameters list.
     *
     * @param  int|null  $page  Page number. Example: 2
     * @param  int|null  $pageSize  A limit on the number of objects to be returned, between 1 and 100. Default: 50. Example: 50.
     * @param  string|null  $periodStartFrom  Start date of the period of encounter initiation period. Format DATE '2017-08-16'. Example: 2017-08-16.
     * @param  string|null  $periodStartTo  End date of the period of encounter initiation period. Format DATE '2017-09-16'. Example: 2017-09-16.
     * @param  string|null  $periodEndFrom  Start date of the period of encounter ending period. Format DATE '2017-08-20'. Example: 2017-08-20.
     * @param  string|null  $periodEndTo  End date of the period of treatment ending period. Format DATE '2017-09-20'. Example: 2017-09-20.
     * @param  string|null  $episodeUuid  Example: f48d1b6c-a021-4d6a-a5a4-aee93e152ecc
     * @param  string|null  $incomingReferralUuid  Example: f10aeafb-0df2-4091-bc83-f07e92a100ae
     * @param  string|null  $originEpisodeUuid  Example: d11aeafb-0df2-4091-bc83-f07e92a100af
     * @param  string|null  $managingOrganizationUuid  Example: 887a37a4-c95f-4017-ba11-a000c61bf700
     * @return array
     */
    public static function buildGetEncountersBySearchParams(
        ?int $page = null,
        ?int $pageSize = null,
        ?string $periodStartFrom = null,
        ?string $periodStartTo = null,
        ?string $periodEndFrom = null,
        ?string $periodEndTo = null,
        ?string $episodeUuid = null,
        ?string $incomingReferralUuid = null,
        ?string $originEpisodeUuid = null,
        ?string $managingOrganizationUuid = null
    ): array {
        return [
            'page' => $page,
            'page_size' => $pageSize,
            'period_start_from' => $periodStartFrom,
            'period_start_to' => $periodStartTo,
            'period_end_from' => $periodEndFrom,
            'period_end_to' => $periodEndTo,
            'episode_id' => $episodeUuid,
            'incoming_referral_id' => $incomingReferralUuid,
            'origin_episode_id' => $originEpisodeUuid,
            'managing_organization_id' => $managingOrganizationUuid
        ];
    }

    /**
     * Build an array of parameters for getting procedures using a search parameters list.
     *
     * @param  string  $patientUuid  Unique patient identifier. Example: 7075e0e2-6b57-47fd-aff7-324806efa7e5
     * @param  string|null  $episodeUuid  Unique episode identifier, look into episode in encounter. Example: ef30f210-5328-4f48-bfe6-c7150d4737a6
     * @param  string|null  $status  Status of procedure. Example: completed
     * @param  string|null  $usedReferenceUuid  Items used during procedure. Example: ef30f210-5328-4f48-bfe6-c7150d4737a6
     * @param  string|null  $basedOn  Unique service request identifier. Example: ef30f210-5328-4f48-bfe6-c7150d4737a6
     * @param  string|null  $code  Example: 9075e0e2-6b57-47fd-aff7-324806efa7e6
     * @param  string|null  $managingOrganizationUuid  Unique legal entity identifier. Example: 7075e0e2-6b57-47fd-aff7-324806efa7e5
     * @param  string|null  $encounterUuid  Unique encounter identifier. Example: 7075e0e2-6b57-47fd-aff7-324806efa7e5
     * @param  string|null  $originEpisodeUuid  Unique episode identifier, look into episode in procedures.origin_episode. Example: ef30f210-5328-4f48-bfe6-c7150d4737a6
     * @param  string|null  $deviceUuid  Device identifier. Example: ef30f210-5328-4f48-bfe6-c7150d4737a6
     * @return array
     */
    public static function buildGetProceduresBySearchParams(
        string $patientUuid,
        ?string $episodeUuid = null,
        ?string $status = null,
        ?string $usedReferenceUuid = null,
        ?string $basedOn = null,
        ?string $code = null,
        ?string $managingOrganizationUuid = null,
        ?string $encounterUuid = null,
        ?string $originEpisodeUuid = null,
        ?string $deviceUuid = null
    ): array {
        return [
            'patient_id' => $patientUuid,
            'episode_id' => $episodeUuid,
            'status' => $status,
            'used_reference_id' => $usedReferenceUuid,
            'based_on' => $basedOn,
            'code' => $code,
            'managing_organization_id' => $managingOrganizationUuid,
            'encounter_id' => $encounterUuid,
            'origin_episode_id' => $originEpisodeUuid,
            'device_id' => $deviceUuid
        ];
    }

    /**
     * Build an array of parameters for getting procedures using a search parameters list.
     *
     * @param  int|null  $page  Page number. Example: 2
     * @param  int|null  $pageSize  A limit on the number of objects to be returned, between 1 and 100. Default: 50. Example: 50
     * @param  string|null  $code  Example: 12345
     * @param  string|null  $encounterUuid  Example: 09dc3ed7-2169-45d8-8fa3-d918c6839bf9
     * @param  string|null  $contextEpisodeUuid  Example: 09dc3ed7-2169-45d8-8fa3-d918c6839bf9
     * @param  string|null  $originEpisodeUuid  Example: 09dc3ed7-2169-45d8-8fa3-d918c6839bf9
     * @param  string|null  $issuedFrom  Example: 1990-01-01
     * @param  string|null  $issuedTo  Example: 2000-01-01
     * @param  string|null  $basedOn  Example: 09dc3ed7-2169-45d8-8fa3-d918c6839bf9
     * @param  string|null  $managingOrganizationUuid  Example: 19dc3ed7-2169-45d8-8fa3-d918c6839bf9
     * @param  string|null  $specimenUuid  Example: 90dc3ed7-2169-45d8-8fa3-d918c6839b21
     * @return array
     */
    public static function buildGetDiagnosticReportsBySearchParams(
        ?int $page = null,
        ?int $pageSize = null,
        ?string $code = null,
        ?string $encounterUuid = null,
        ?string $contextEpisodeUuid = null,
        ?string $originEpisodeUuid = null,
        ?string $issuedFrom = null,
        ?string $issuedTo = null,
        ?string $basedOn = null,
        ?string $managingOrganizationUuid = null,
        ?string $specimenUuid = null
    ): array {
        return [
            'page' => $page,
            'page_size' => $pageSize,
            'code' => $code,
            'encounter_id' => $encounterUuid,
            'context_episode_id' => $contextEpisodeUuid,
            'origin_episode_id' => $originEpisodeUuid,
            'issued_from' => $issuedFrom,
            'issued_to' => $issuedTo,
            'based_on' => $basedOn,
            'managing_organization_id' => $managingOrganizationUuid,
            'specimen_id' => $specimenUuid
        ];
    }

    /**
     * Build an array of parameters for submitting encounter package.
     *
     * @param  array  $data
     * @param  string  $signedData
     * @return array
     */
    public static function buildSubmitEncounterPackage(array $data, string $signedData): array
    {
        return [
            'visit' => (object)[
                'id' => $data['visit']['identifier']['value'],
                'period' => (object)[
                    'start' => $data['period']['start'],
                    'end' => $data['period']['end']
                ]
            ],
            'signed_data' => $signedData
        ];
    }
}
