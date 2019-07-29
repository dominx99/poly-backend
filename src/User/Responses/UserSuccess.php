<?php declare(strict_types = 1);

namespace App\User\Responses;

use App\System\Responses\Success;
use App\User\Application\Query\UserView;

class UserSuccess extends Success
{
    /**
     * @param \App\User\Application\Query\UserView $user
     */
    public function __construct(UserView $user)
    {
        parent::__construct([
            'id'       => $user->id(),
            'email'    => $user->email(),
            'world_id' => $user->worldId(),
        ]);
    }
}
