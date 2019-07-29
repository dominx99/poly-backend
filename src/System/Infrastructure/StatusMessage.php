<?php declare(strict_types = 1);

namespace App\System\Infrastructure;

class StatusMessage
{
    const SUCCESS = 'success';
    const FAIL    = 'fail';

    const ERROR = 'Something went wrong';

    const LOGIN_ERROR        = 'Credentials are wrong';
    const LOGIN_SOCIAL_ERROR = 'User was not found';

    const TOKEN_REQUIRED = 'Token is required';

    const ALREADY_IN_GAME          = 'You are already in game';
    const USER_NOT_BELONG_TO_WORLD = 'User does not belong to this world.';
}
