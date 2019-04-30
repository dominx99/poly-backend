<?php declare (strict_types = 1);

namespace Wallet\User\Responses;

use Wallet\System\Infrastructure\JWT;
use Wallet\System\Responses\Success;
use Wallet\User\Application\Query\UserView;

class LoginSuccess extends Success
{
    /**
     * @param \Wallet\User\Application\Query\UserView $user
     */
    public function __construct(UserView $user)
    {
        parent::__construct([
            'token' => JWT::fromUser($user),
            'email' => $user->email(),
        ]);
    }
}
