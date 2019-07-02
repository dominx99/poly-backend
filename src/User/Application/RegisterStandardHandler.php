<?php declare (strict_types = 1);

namespace App\User\Application;

use App\User\Infrastructure\ORMUsers;

class RegisterStandardHandler
{
    /**
     * @var \App\User\Infrastructure\ORMUsers
     */
    protected $users;

    /**
     * @param \App\User\Infrastructure\ORMUsers $users
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
