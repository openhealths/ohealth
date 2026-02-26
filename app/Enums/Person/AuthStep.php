<?php

declare(strict_types=1);

namespace App\Enums\Person;

use App\Traits\EnumUtils;

/**
 * List of constants for navigation by modals for interaction with auth methods.
 */
enum AuthStep: int
{
    use EnumUtils;

    case INITIAL = 0;
    case CHANGE_PHONE_INITIAL = 1;
    case ASK_OTP_PERMISSION = 2;
    case VERIFY_PHONE = 3;
    case NO_PHONE_ACCESS = 4;
    case COMPLETE_VERIFICATION = 5;
    case CHANGE_FROM_OFFLINE = 6;
    case CHANGE_PHONE = 7;
    case CHANGE_ALIAS = 8;
    case UPDATE_ALIAS = 9;
    case ADD_NEW_BY_SMS = 10;
    case APPROVE_ADDING_BY_SMS = 11;
    case ADD_NEW_BY_DOCUMENT = 12;
    case ADD_NEW_BY_THIRD_PERSON = 13;
    case ADD_ALIAS_FOR_THIRD_PERSON = 14;
    case APPROVE_ADDING_NEW_METHOD = 15;
    case APPROVE_DEACTIVATING_METHOD = 16;
}
