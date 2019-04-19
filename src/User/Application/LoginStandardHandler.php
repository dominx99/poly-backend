<?php declare (strict_types = 1);

namespace Wallet\User\Application;

use Wallet\System\Contracts\Query;
use Wallet\System\Contracts\Responsable;
use Wallet\User\Infrastructure\DbalUsers;
use Wallet\User\Responses\LoginFail;
use Wallet\User\Responses\LoginSuccess;

class LoginStandardHandler implements Query
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
     * @param  LoginStandard $command
     * @return \Wallet\System\Contracts\Responsable
     */
    public function execute(LoginStandard $command): Responsable
    {
        $user = $this->users->getByEmail($command->email());

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
