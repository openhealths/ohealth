<?php

declare(strict_types=1);

namespace App\Classes\eHealth\Api;

use App\Classes\eHealth\EHealthRequest as Request;
use App\Classes\eHealth\EHealthResponse;
use App\Exceptions\EHealth\EHealthResponseException;
use App\Exceptions\EHealth\EHealthValidationException;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;

class Condition extends Request
{
    protected const string URL = '/api/patients';

    /**
     * Return a condition context record by IDs.
     *
     * @param  string  $patientUuid
     * @param  string  $episodeUuid
     * @param  array  $data
     * @return PromiseInterface|EHealthResponse
     * @throws ConnectionException|EHealthValidationException|EHealthResponseException
     */
    public function getInEpisodeContext(
        string $patientUuid,
        string $episodeUuid,
        array $data = []
    ): PromiseInterface|EHealthResponse {
        return $this->get(self::URL . "/$patientUuid/episodes/$episodeUuid/conditions", $data);
    }
}
