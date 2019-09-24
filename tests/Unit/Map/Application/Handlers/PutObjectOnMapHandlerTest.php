<?php declare(strict_types=1);

namespace Tests;

use App\User\Contracts\UserQueryRepository;
use Tests\BaseTestCase;
use App\User\Application\Query\UserView;

final class PutObjectOnMapHandlerTest extends BaseTestCase
{
    /** @test */
    public function that_puts_object_on_map()
    {
        $stub = $this->createMock(UserQueryRepository::class);

        $stub->method('find')->willReturn(
            UserView::createFromDatabase([
                'id' => 'daads',
                'email' => 'sdasad',
                'password' => 'wsda',
                'world_id' => 'dsasad',
            ])
        );

        $this->assertEquals([], $stub->find('dsa'));
    }
}
