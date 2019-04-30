<?php declare (strict_types = 1);

namespace Wallet\Wallet\Application;

use Wallet\Wallet\Application\CreateWallet;
use Wallet\Wallet\Infrastructure\ORMWallets;

class CreateWalletHandler
{
    /**
     * @var \Wallet\Wallet\Infrastructure\ORMWallets
     */
    private $wallets;

    /**
     * @param \Wallet\Wallet\Infrastructure\ORMWallets $wallets
     */
    public function __construct(ORMWallets $wallets)
    {
        $this->wallets = $wallets;
    }

    /**
     * @param \Wallet\Wallet\Application\CreateWallet $command
     * @return void
     */
    public function handle(CreateWallet $command): void
    {
        $this->wallets->add([
            'name'     => $command->name(),
            'slug'     => $command->slug(),
            'owner_id' => $command->ownerId(),
        ]);
    }
}
