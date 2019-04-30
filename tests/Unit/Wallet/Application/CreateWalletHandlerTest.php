<?php declare (strict_types = 1);

namespace Tests\Unit\Wallet\Application;

use Ramsey\Uuid\Uuid;
use Tests\BaseTestCase;
use Tests\DatabaseTrait;
use Wallet\Wallet\Application\CreateWallet;
use Wallet\Wallet\Infrastructure\DbalWallets;

class CreateWalletHandlerTest extends BaseTestCase
{
    use DatabaseTrait;

    public function setUp(): void
    {
        parent::setUp();

        $this->wallets = $this->container->get(DbalWallets::class);
    }

    /** @test */
    public function can_store_valid_wallet()
    {
        $id = Uuid::uuid4();
        $this->createUser($id, 'example@test.com', 'secret');

        $this->system->handle(
            new CreateWallet((string) $id, 'Standard Wallet')
        );

        $this->assertDatabaseHas('wallets', [
            'name'     => 'Standard Wallet',
            'slug'     => 'standard-wallet',
            'owner_id' => $id,
        ]);

        $wallet = $this->wallets->findBySlug('standard-wallet');

        $this->assertDatabaseHas('user_wallet', [
            'user_id'   => $id,
            'wallet_id' => $wallet->id(),
        ]);
    }
}
