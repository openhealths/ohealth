<?php

declare(strict_types=1);

namespace App\Classes\eHealth\Api;

use App\Classes\eHealth\EHealthRequest as Request;
use App\Classes\eHealth\EHealthResponse;
use App\Exceptions\EHealth\EHealthResponseException;
use App\Exceptions\EHealth\EHealthValidationException;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;

class DiagnosticReport extends Request
{
    protected const string URL = '/api/patients';

    /**
     * Create the diagnostic report for patient.
     *
     * @param  string  $uuid  Person UUID
     * @param  array  $data
     * @return EHealthResponse|PromiseInterface
     * @throws ConnectionException|EHealthValidationException|EHealthResponseException
     */
    public function create(string $uuid, array $data = []): PromiseInterface|EHealthResponse
    {
        return $this->post(self::URL . "/$uuid/diagnostic_report_package", $data);
    }
}
