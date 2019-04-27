<?php declare (strict_types = 1);

namespace Wallet\User\Application\Exception;

use Wallet\System\Infrastructure\StatusMessage;

class NotFoundSocialUserException extends \Exception
{
    protected $message = StatusMessage::LOGIN_SOCIAL_ERROR;
}
