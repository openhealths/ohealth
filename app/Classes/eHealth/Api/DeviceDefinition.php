<?php

declare(strict_types=1);

namespace App\Classes\eHealth\Api;

use App\Classes\eHealth\EHealthRequest as Request;
use App\Classes\eHealth\EHealthResponse;
use App\Exceptions\EHealth\EHealthResponseException;
use App\Exceptions\EHealth\EHealthValidationException;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;

class DeviceDefinition extends Request
{
    protected const string URL = '/api/v2/device_definitions';

    /**
     * Search all active device definitions in the system.
     *
     * @param  $query
     * @return PromiseInterface|EHealthResponse
     * @throws ConnectionException|EHealthValidationException|EHealthResponseException
     */
    public function getMany($query = null): PromiseInterface|EHealthResponse
    {
        $query = array_merge([
            self::QUERY_PARAM_PAGE_SIZE => config('ehealth.api.page_size')
        ], $query ?? []);

        return $this->get(self::URL, $query);
    }
}
