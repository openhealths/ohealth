<?php

declare(strict_types=1);

namespace App\Traits;

use App\Classes\eHealth\Api\PatientApi;
use App\Classes\eHealth\Exceptions\ApiException;
use App\Core\Arr;
use App\Models\MedicalEvents\Sql\Condition;
use App\Models\MedicalEvents\Sql\Encounter;
use App\Models\MedicalEvents\Sql\Observation;
use App\Repositories\MedicalEvents\Repository;
use Illuminate\Support\Facades\Log;
use Throwable;

trait HandlesReasonReferences
{
    /**
     * Handle details of procedure reason references.
     *
     * @param  array  $procedure
     * @return void
     */
    public function processReasonReferences(array $procedure): void
    {
        if (!isset($procedure['reasonReferences'])) {
            return;
        }

        foreach ($procedure['reasonReferences'] as $reasonReference) {
            if ($reasonReference['identifier']['type']['coding'][0]['code'] === 'condition') {
                $this->ensureConditionExists($reasonReference['identifier']['value']);
            } else {
                $this->ensureObservationExists($reasonReference['identifier']['value']);
            }
        }
    }

    /**
     * Checks if a condition exists and creates it if necessary
     *
     * @param  string  $uuid
     * @return void
     */
    public function ensureConditionExists(string $uuid): void
    {
        if (Condition::whereUuid($uuid)->exists()) {
            return;
        }

        try {
            $conditionData = PatientApi::getConditionById($this->patientUuid, $uuid);
            $encounterId = $this->ensureEncounterExist($conditionData['context']['identifier']['value']);

            if ($encounterId) {
                Repository::condition()->store([Arr::toCamelCase($conditionData)], $encounterId);
            }
        } catch (ApiException|Throwable $e) {
            session()?->flash('error', 'Виникла помилка. Зверніться до адміністратора.');

            Log::error('Failed while ensuring condition existence', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        }
    }

    /**
     * Check is encounter exist, if not create it. Return created or existed ID of encounter.
     *
     * @param  string  $encounterUuid
     * @return int|null
     */
    private function ensureEncounterExist(string $encounterUuid): ?int
    {
        $encounterId = Encounter::whereUuid($encounterUuid)->value('id');

        if ($encounterId) {
            return $encounterId;
        }

        try {
            $encounterData = PatientApi::getEncounterById($this->patientUuid, $encounterUuid);

            return Repository::encounter()->store($encounterData, $this->patientId);
        } catch (ApiException|Throwable $e) {
            session()?->flash('error', 'Виникла помилка. Зверніться до адміністратора.');

            Log::error('Failed while ensuring encounter existence', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        }

        return null;
    }

    /**
     * Checks for the existence of an observation and creates it if necessary.
     *
     * @param  string  $uuid
     * @return void
     */
    private function ensureObservationExists(string $uuid): void
    {
        if (Observation::whereUuid($uuid)->exists()) {
            return;
        }

        try {
            $observationData = PatientApi::getObservationById($this->patientUuid, $uuid);
            $encounterId = $this->ensureEncounterExist($observationData['context']['identifier']['value']);

            if ($encounterId) {
                Repository::observation()->store([Arr::toCamelCase($observationData)], $encounterId);
            }
        } catch (ApiException|Throwable $e) {
            session()?->flash('error', 'Виникла помилка. Зверніться до адміністратора.');

            Log::error('Failed while ensuring observation existence', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        }
    }
}
