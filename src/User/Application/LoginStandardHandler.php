<?php declare (strict_types = 1);

namespace App\User\Application;

use App\System\Contracts\Responsable;
use App\User\Infrastructure\DbalUsers;
use App\User\Responses\LoginFail;
use App\User\Responses\LoginSuccess;

class LoginStandardHandler
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
     * @param  \App\User\Application\LoginStandard $command
     * @return \App\System\Contracts\Responsable
     */
    public function execute(LoginStandard $command): Responsable
    {
        $user = $this->users->findByEmail($command->email());

        if (!$user) {
            return new LoginFail();
        }

        $result = password_verify($command->password(), $user->password());

        if (!$result) {
            return new LoginFail();
        }

        return new LoginSuccess($user);
    }
}
