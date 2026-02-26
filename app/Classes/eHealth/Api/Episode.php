<?php

declare(strict_types=1);

namespace App\Classes\eHealth\Api;

use App\Classes\eHealth\EHealthRequest as Request;
use App\Classes\eHealth\EHealthResponse;
use App\Exceptions\EHealth\EHealthResponseException;
use App\Exceptions\EHealth\EHealthValidationException;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;

class Episode extends Request
{
    protected const string URL = '/api/patients';

    /**
     * Return an episode records by id.
     *
     * @param  string  $patientUuid
     * @param  string|array  $query  https://medicaleventsmisapi.docs.apiary.io/#reference/medical-events/procedures/get-procedures-by-search-params
     * @return PromiseInterface|EHealthResponse
     * @throws ConnectionException|EHealthValidationException|EHealthResponseException
     */
    public function getManyBySearchParams(
        string $patientUuid,
        string|array $query = []
    ): PromiseInterface|EHealthResponse {
        return $this->get(self::URL . "/$patientUuid/episodes", $query);
    }
}
