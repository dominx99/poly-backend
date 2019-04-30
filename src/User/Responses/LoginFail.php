<?php declare (strict_types = 1);

namespace Wallet\User\Responses;

use Slim\Http\StatusCode;
use Wallet\System\Infrastructure\StatusMessage;
use Wallet\System\Responses\Fail;

class LoginFail extends Fail
{
    /**
     * @param string $error
     */
    public function __construct(string $error = StatusMessage::LOGIN_ERROR)
    {
        parent::__construct(['error' => $error], StatusCode::HTTP_UNAUTHORIZED);
    }
}
