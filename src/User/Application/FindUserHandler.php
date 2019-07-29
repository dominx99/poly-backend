<?php declare(strict_types = 1);

namespace App\User\Application;

use App\User\Contracts\UserQueryRepository;

class FindUserHandler
{
    /**
     * @var \App\User\Contracts\UserQueryRepository
     */
    private $users;

    /**
     * @param \App\User\Contracts\UserQueryRepository $users
     */
    public function __construct(UserQueryRepository $users)
    {
        $this->users = $users;
    }

    /**
     * @param \App\User\Application\FindUser $command
     * @return \App\User\Application\Query\UserView|null
     */
    public function execute(FindUser $command)
    {
        return $this->users->find($command->userId());
    }
}
