<?php declare (strict_types = 1);

namespace Wallet\User\Responses;

use Slim\Http\Response;
use Slim\Http\StatusCode;
use Wallet\System\Contracts\Responsable;
use Wallet\System\Infrastructure\StatusMessage;

class LoginFail implements Responsable
{
    /**
     * @return \Slim\Http\Response
     */
    public function toResponse(): Response
    {
        return (new Response())->withJson([
            'error' => StatusMessage::LOGIN_ERROR,
        ], StatusCode::HTTP_UNAUTHORIZED);
    }
}
