<?php declare(strict_types = 1);

namespace App\User\Responses;

use Slim\Http\StatusCode;
use App\System\Infrastructure\StatusMessage;
use App\System\Responses\Fail;

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
