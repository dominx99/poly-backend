<?php declare(strict_types = 1);

namespace Tests\Feature\User\Presentation;

use Tests\BaseTestCase;
use Tests\DatabaseTrait;
use Ramsey\Uuid\Uuid;
use App\World\Domain\World\Status;
use App\Map\Application\MapGenerate;
use App\World\Application\CreateWorld;
use App\World\Application\UserJoinWorld;

class MapControllerTest extends BaseTestCase
{
    use DatabaseTrait;

    /** @test */
    public function that_shows_map()
    {
        $this->auth();
        $worldId = (string) Uuid::uuid4();
        $this->system->handle(new CreateWorld($worldId, Status::CREATED));
        $this->system->handle(new UserJoinWorld((string) $this->userId, $worldId));
        $this->system->handle(new MapGenerate($worldId, (string) Uuid::uuid4()));

        $this->assertDatabaseHas('users', [
            'id'       => $this->userId,
            'world_id' => $worldId,
        ]);

        $this->assertDatabaseHas('maps', [
            'world_id' => $worldId,
        ]);

        $this->assertDatabaseHas('worlds', [
            'id' => $worldId,
        ]);

        $response = $this->runApp('GET', "api/map/{$worldId}");
        var_dump((string) $response->getBody());die();

        $data = json_decode((string) $response->getBody(), true)['data'];

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('map', $data);
        $this->assertArrayHasKey('fields', $data['map']);
    }
}
