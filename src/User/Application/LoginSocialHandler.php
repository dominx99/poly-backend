<?php declare (strict_types = 1);

namespace Wallet\User\Application;

use Wallet\System\Contracts\Query;
use Wallet\System\Contracts\Responsable;
use Wallet\System\Infrastructure\StatusMessage;
use Wallet\User\Application\LoginSocial;
use Wallet\User\Infrastructure\DbalUsers;
use Wallet\User\Responses\LoginFail;
use Wallet\User\Responses\LoginSuccess;

class LoginSocialHandler implements Query
{
    /**
     * @var \Wallet\User\Infrastructure\DbalUsers
     */
    protected $users;

    /**
     * @param \Wallet\User\Infrastructure\DbalUsers $users
     */
    public function __construct(DbalUsers $users)
    {
        $this->users = $users;
    }

    /**
     * @param \Wallet\User\Application\LoginSocial $command
     * @return \Wallet\System\Contracts\Responsable
     */
    public function execute(LoginSocial $command): Responsable
    {
        if (!$user = $this->users->findByEmail($command->email())) {
            return new LoginFail(StatusMessage::LOGIN_SOCIAL_ERROR);
        }

        return new LoginSuccess($user);
    }
}
