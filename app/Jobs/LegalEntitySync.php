<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Core\EHealthJob;
use GuzzleHttp\Promise\PromiseInterface;
use App\Classes\eHealth\EHealthResponse;

/**
 * This job is responsible for finalizing a full synchronization operation between different data sources
 *
 * @package App\Jobs
 */
class LegalEntitySync extends EHealthJob
{
    public const string BATCH_NAME = 'LegalEntitySync';

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        echo 'Starting LegalEntitySync for legalEntity : ' . $this->legalEntity->id . PHP_EOL;

        parent::handle();

        $this->sendEntityNotification('legal_entity', 'completed');
    }

    // Get data from EHealth API (here it mostly dummy method)
    protected function sendRequest(string $token): PromiseInterface|EHealthResponse|null
    {
        return null;
    }

    // Store or update data in the database (here it mostly dummy method)
    protected function processResponse(?EHealthResponse $response): void
    {
    }

    /**
     * Get additional middleware configurations for the job.
     *
     * @return array Returns an array of middleware configurations to be applied to the job
     */
    protected function getAdditionalMiddleware(): array
    {
        return [];
    }

    // Get next entity job if needed
    protected function getNextEntityJob(): ?EHealthJob
    {
        $nextEntity = $this->nextEntity ?? $this->getConfidantPersonStartJob($this->legalEntity, null);

        return $this->standalone || !$nextEntity
            ? new CompleteSync($this->legalEntity, isFirstLogin: $this->isFirstLogin)
            : $nextEntity;
    }
}
