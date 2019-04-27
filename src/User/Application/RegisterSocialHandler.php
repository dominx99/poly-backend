<?php declare (strict_types = 1);

namespace Wallet\User\Application;

use Wallet\System\Contracts\Handler;
use Wallet\User\Application\RegisterSocial;
use Wallet\User\Infrastructure\DbalUsers;
use Wallet\User\Infrastructure\ORMUsers;

class RegisterSocialHandler implements Handler
{
    /**
     * @var \Wallet\User\Infrastructure\DbalUsers
     */
    protected $dbalUsers;

    /**
     * @param \Wallet\User\Infrastructure\DbalUsers $dbalUsers
     * @param \Wallet\User\Infrastructure\ORMUsers $ormUsers
     */
    public function __construct(DbalUsers $dbalUsers, ORMUsers $ormUsers)
    {
        $this->dbalUsers = $dbalUsers;
        $this->ormUsers  = $ormUsers;
    }

    /**
     * @param \Wallet\User\Application\RegisterSocial $command
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
