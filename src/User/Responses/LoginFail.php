<?php declare (strict_types = 1);

namespace Wallet\User\Responses;

use Slim\Http\Response;
use Slim\Http\StatusCode;
use Wallet\System\Contracts\Responsable;
use Wallet\System\Infrastructure\StatusMessage;

class LoginFail implements Responsable
{
    /**
     * @param string $message
     */
    public function __construct(string $message = StatusMessage::LOGIN_ERROR)
    {
        $this->message = $message;
    }

    /**
     * @return \Slim\Http\Response
     */
    public function toResponse(): Response
    {
        return (new Response())->withJson([
            'error' => $this->message,
        ], StatusCode::HTTP_UNAUTHORIZED);
    }
}
