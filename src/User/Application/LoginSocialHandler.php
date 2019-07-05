<?php declare(strict_types = 1);

namespace App\User\Application;

use App\System\Contracts\Responsable;
use App\System\Infrastructure\StatusMessage;
use App\User\Infrastructure\DbalUsers;
use App\User\Responses\LoginFail;
use App\User\Responses\LoginSuccess;

class LoginSocialHandler
{
    /**
     * @var \App\User\Infrastructure\DbalUsers
     */
    protected $users;

    /**
     * @param \App\User\Infrastructure\DbalUsers $users
     */
    public function __construct(DbalUsers $users)
    {
        $this->users = $users;
    }

    /**
     * @param \App\User\Application\LoginSocial $command
     * @return \App\System\Contracts\Responsable
     */
    public function execute(LoginSocial $command): Responsable
    {
        if (!$user = $this->users->findByEmail($command->email())) {
            return new LoginFail(StatusMessage::LOGIN_SOCIAL_ERROR);
        }

        return new LoginSuccess($user);
    }
}
