<?php declare (strict_types = 1);

namespace App\User\Responses;

use App\System\Infrastructure\JWT;
use App\System\Responses\Success;
use App\User\Application\Query\UserView;

class LoginSuccess extends Success
{
    /**
     * @param \App\User\Application\Query\UserView $user
     */
    public function __construct(UserView $user)
    {
        parent::__construct([
            'token' => JWT::fromUser($user),
            'email' => $user->email(),
        ]);
    }
}
