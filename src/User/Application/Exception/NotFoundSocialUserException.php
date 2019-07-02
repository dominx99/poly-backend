<?php declare (strict_types = 1);

namespace App\User\Application\Exception;

use App\System\Infrastructure\StatusMessage;

class NotFoundSocialUserException extends \Exception
{
    protected $message = StatusMessage::LOGIN_SOCIAL_ERROR;
}
