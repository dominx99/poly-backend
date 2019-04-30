<?php declare (strict_types = 1);

namespace Tests\Unit\Wallet\Presentation;

use Ramsey\Uuid\Uuid;
use Tests\BaseTestCase;
use Tests\DatabaseTrait;
use Wallet\System\Infrastructure\StatusMessage;
use Wallet\Wallet\Infrastructure\DbalWallets;

class WalletControllerTest extends BaseTestCase
{
    use DatabaseTrait;

    public function setUp(): void
    {
        parent::setUp();

        $this->wallets = $this->container->get(DbalWallets::class);
    }

    /** @test */
    public function that_store_wallet_works()
    {
        $id = Uuid::uuid4();

        $this->createUser($id, 'example@test.com', 'secret');
        $this->authById((string) $id);

        $response = $this->runApp('POST', '/api/wallet', [
            'name' => 'Standard Wallet',
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertDatabaseHas('wallets', [
            'name'     => 'Standard Wallet',
            'slug'     => 'standard-wallet',
            'owner_id' => $id,
        ]);

        $walletView = $this->wallets->findBySlug('standard-wallet');

        $this->assertDatabaseHas('user_wallet', [
            'user_id'   => $id,
            'wallet_id' => $walletView->id(),
        ]);
    }

    /** @test */
    public function that_wrong_values_will_return_validation_fail()
    {
        $id = Uuid::uuid4();

        $this->createUser($id, 'example@test.com', 'secret');
        $this->authById((string) $id);

        $response = $this->runApp('POST', '/api/wallet', [
            'name' => 'ab',
        ]);

        $this->assertEquals(400, $response->getStatusCode());

        $body = json_decode((string) $response->getBody(), true);

        $this->assertEquals(StatusMessage::FAIL, $body['status']);
        $this->assertArrayHasKey('name', $body['errors']);
    }
}
