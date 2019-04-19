<?php declare (strict_types = 1);

namespace Wallet\System\Infrastructure;

use Firebase\JWT\JWT as FirebaseJWT;
use Wallet\User\Application\Query\UserView;

class JWT extends FirebaseJWT
{
    /**
     * @param  \Wallet\User\Application\Query\UserView $user
     * @return string
     */
    public static function fromUser(UserView $user): string
    {
        return static::encode([
            'id' => $user->id(),
        ], getenv('JWT_KEY'));
    }
}
