<?php declare (strict_types = 1);

namespace App\User\Application;

use App\User\Application\RegisterSocial;
use App\User\Infrastructure\DbalUsers;
use App\User\Infrastructure\ORMUsers;

class RegisterSocialHandler
{
    /**
     * @var \App\User\Infrastructure\DbalUsers
     */
    protected $dbalUsers;

    /**
     * @var \App\User\Infrastructure\ORMUsers
     */
    protected $ormUsers;

    /**
     * @param \App\User\Infrastructure\DbalUsers $dbalUsers
     * @param \App\User\Infrastructure\ORMUsers $ormUsers
     */
    public function __construct(DbalUsers $dbalUsers, ORMUsers $ormUsers)
    {
        $this->dbalUsers = $dbalUsers;
        $this->ormUsers  = $ormUsers;
    }

    /**
     * @param \App\User\Application\RegisterSocial $command
     * @return void
     */
    public function handle(RegisterSocial $command): void
    {
        $user = $this->dbalUsers->findByEmail($command->email());

        if (!$user) {
            $this->ormUsers->addWithProvider([
                'email'         => $command->email(),
                'provider_name' => $command->provider(),
            ]);
        }
    }
}
