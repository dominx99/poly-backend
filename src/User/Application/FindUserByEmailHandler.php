<?php declare(strict_types = 1);

namespace App\User\Application;

use App\User\Infrastructure\DbalUsers;

class FindUserByEmailHandler
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
     * @param \App\User\Application\FindUserByEmail $command
     * @return null|\App\User\Application\Query\UserView
     */
    public function execute(FindUserByEmail $command)
    {
        return $this->users->findByEmail($command->email());
    }
}
