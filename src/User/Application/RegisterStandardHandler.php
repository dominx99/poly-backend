<?php declare (strict_types = 1);

namespace Wallet\User\Application;

use Wallet\System\Contracts\Handler;
use Wallet\User\Infrastructure\ORMUsers;

class RegisterStandardHandler implements Handler
{
    /**
     * @var \Wallet\User\Infrastructure\ORMUsers
     */
    protected $users;

    /**
     * @param \Wallet\User\Infrastructure\ORMUsers $users
     */
    public function __construct(ORMUsers $users)
    {
        $this->users = $users;
    }

    /**
     * @param  RegisterStandard $command
     * @return void
     */
    public function handle(RegisterStandard $command): void
    {
        $this->users->add([
            'email'    => $command->email(),
            'password' => $command->password(),
        ]);
    }
}
