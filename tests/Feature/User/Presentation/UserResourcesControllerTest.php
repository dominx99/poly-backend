<?php declare(strict_types = 1);

namespace Tests\Feature\User\Presentation;

use Tests\BaseTestCase;
use Tests\DatabaseTrait;
use App\World\Application\CreateWorld;
use Ramsey\Uuid\Uuid;
use App\World\Application\UserJoinWorld;
use App\World\Application\Events\WorldReady;
use App\System\Infrastructure\StatusCode;

class UserResourcesControllerTest extends BaseTestCase
{
    use DatabaseTrait;

    /** @test */
    public function that_returns_correct_resources()
    {
        $this->auth();

        $worldId = (string) Uuid::uuid4();

        $this->system->handle(new CreateWorld($worldId));
        $this->system->handle(new UserJoinWorld((string) $this->userId, $worldId));
        $this->events->dispatch(new WorldReady($worldId));

        $response = $this->runApp('GET', '/api/user/resources');

        $this->assertEquals(StatusCode::HTTP_OK, $response->getStatusCode());

        $data = json_decode((string) $response->getBody(), true)['data'];

        $this->assertSame(300, $data['gold']);
    }
}
