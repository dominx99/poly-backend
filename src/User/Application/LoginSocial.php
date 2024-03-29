<?php declare(strict_types = 1);

namespace App\User\Application;

use Overtrue\Socialite\User;
use App\System\Contracts\Command;

class LoginSocial implements Command
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
