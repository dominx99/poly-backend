<?php declare (strict_types = 1);

namespace Tests\Unit\User;

use Ramsey\Uuid\Uuid;
use Tests\BaseTestCase;
use Tests\DatabaseTrait;
use App\User\Infrastructure\DbalUsers;
use App\User\Infrastructure\UserFilters;

class QueryUsersTest extends BaseTestCase
{
    use DatabaseTrait;

    public function setUp(): void
    {
        parent::setUp();

        $this->users = $this->container->get(DbalUsers::class);
    }

    /** @test */
    public function that_find_method_wokrs()
    {
        $id = Uuid::uuid4();

        $this->createUser($id, 'example@test.com', 'test');

        $userView = $this->users->find(new UserFilters(['email' => 'example@test.com']));

        $this->assertEquals($id, $userView->id());
    }
}
