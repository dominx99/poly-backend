<?php declare (strict_types = 1);

namespace Wallet\User\Application;

use Overtrue\Socialite\User;
use Wallet\System\Contracts\Query;

class LoginSocial implements Query
{
    /**
     * @var string
     */
    private $email;

    /**
     * @param \Overtrue\Socialite\User $socialUser
     */
    public function __construct(User $socialUser)
    {
        $this->email = $socialUser->getEmail();
    }

    /**
     * @return string
     */
    public function email(): string
    {
        return $this->email;
    }
}
