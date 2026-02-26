<?php

declare(strict_types=1);

namespace App\Enums\Person;

use App\Traits\EnumUtils;

/**
 * List of actions used for interaction with the auth method endpoint.
 *
 * see: https://uaehealthapi.docs.apiary.io/#reference/public.-medical-service-provider-integration-layer/persons/create-authentication-method-request
 */
enum AuthenticationMethodAction: string
{
    use EnumUtils;

    case DEACTIVATE = 'DEACTIVATE';
    case INSERT = 'INSERT';
    case UPDATE = 'UPDATE';
}
