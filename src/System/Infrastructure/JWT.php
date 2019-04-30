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

    /**
     * @param string $token
     * @param string|array $key
     * @param array $allowedAlgs
     * @return void
     */
    public static function decodeFromBearer(string $token, $key, array $allowedAlgs = [])
    {
        $token = substr($token, 7);

        return (array) static::decode($token, $key, $allowedAlgs);
    }
}
