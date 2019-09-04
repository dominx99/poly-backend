<?php declare(strict_types = 1);

namespace App\User\Application;

use App\System\Infrastructure\StatusMessage;
use App\User\Responses\UserResourcesSuccess;
use App\System\Responses\Fail;
use App\System\Contracts\Responsable;
use App\User\Infrastructure\DbalUsers;

class GetUserResourcesHandler
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
     * @param \App\User\Application\GetUserResources $command
     * @return \App\System\Contracts\Responsable
     */
    public function execute(GetUserResources $command): Responsable
    {
        if (! $resources = $this->users->getResources($command->userId())) {
            return new Fail(['error' => StatusMessage::FAILED_LOAD_USER_RESOURCES]);
        }

        return new UserResourcesSuccess($resources);
    }
}
