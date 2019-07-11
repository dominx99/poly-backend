<?php declare(strict_types = 1);

namespace App\User\Application;

use App\User\Application\AlreadyInGame;
use App\User\Contracts\UserQueryRepository;

class AlreadyInGameHandler
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
     * @param \App\User\Application\AlreadyInGame $command
     * @return bool
     */
    public function execute(AlreadyInGame $command): bool
    {
        return $this->users->isInGame($command->userId());
    }
}
