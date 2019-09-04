<?php declare(strict_types = 1);

namespace App\User\Responses;

use App\System\Infrastructure\StatusMessage;
use App\System\Responses\Fail;
use App\System\Infrastructure\StatusCode;

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
