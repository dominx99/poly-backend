<?php declare (strict_types = 1);

namespace App\System\Infrastructure;

use Firebase\JWT\JWT as FirebaseJWT;
use App\User\Application\Query\UserView;

class JWT extends FirebaseJWT
{
    /**
     * @param  \App\User\Application\Query\UserView $user
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
     * @return array
     */
    public static function decodeFromBearer(string $token, $key, array $allowedAlgs = []): array
    {
        $token = substr($token, 7);

        return (array) static::decode($token, $key, $allowedAlgs);
    }
}
