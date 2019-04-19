<?php declare (strict_types = 1);

namespace Wallet\User\Responses;

use Slim\Http\Response;
use Wallet\System\Contracts\Responsable;
use Wallet\System\Infrastructure\JWT;
use Wallet\User\Application\Query\UserView;

class LoginSuccess implements Responsable
{
    /**
     * @var \Wallet\User\Application\Query\UserView
     */
    protected $user;

    /**
     * @param \Wallet\User\Application\Query\UserView $user
     */
    public function __construct(UserView $user)
    {
        $this->user = $user;
    }

    /**
     * @return \Slim\Http\Response
     */
    public function toResponse(): Response
    {
        return (new Response())->withJson([
            'data' => [
                'token' => JWT::fromUser($this->user),
                'email' => $this->user->email(),
            ],
        ], 200);
    }
}
