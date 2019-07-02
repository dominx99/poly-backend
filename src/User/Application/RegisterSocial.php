<?php declare (strict_types = 1);

namespace App\User\Application;

use Overtrue\Socialite\User;
use App\System\Contracts\Command;

class RegisterSocial implements Command
{
    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $provider;

    /**
     * @param \Overtrue\Socialite\User $user
     */
    public function __construct(User $user)
    {
        $this->email    = $user->getEmail();
        $this->provider = $user->getProviderName();
    }

    /**
     * @return string
     */
    public function email(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function provider(): string
    {
        return $this->provider;
    }
}
