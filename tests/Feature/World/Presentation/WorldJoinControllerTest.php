<?php declare(strict_types = 1);

namespace Tests\World\Presentation;

use Tests\BaseTestCase;
use Tests\DatabaseTrait;
use App\World\Application\GetWorlds;
use App\World\Domain\World\Status;

class WorldJoinControllerTest extends BaseTestCase
{
    use DatabaseTrait;

    public function setUp(): void
    {
        parent::setUp();

        $this->auth();
    }

    /** @test */
    public function that_creates_world_if_not_exists()
    {
        $this->auth();

        $response = $this->runApp('POST', '/api/worlds');

        $this->assertEquals(200, $response->getStatusCode());

        $worlds = $this->system->execute(new GetWorlds());

        $this->assertNotNull($worlds);
        $this->assertCount(1, $worlds);

        $this->assertDatabaseHas('users', [
            'world_id' => $worlds[0]->id(),
        ]);
    }

    /** @test */
    public function that_joins_to_existing_world()
    {
        $response = $this->runApp('POST', '/api/worlds');
        $this->assertEquals(200, $response->getStatusCode());

        $worlds = $this->system->execute(new GetWorlds());
        $this->assertNotNull($worlds);
        $this->assertCount(1, $worlds);

        $this->assertDatabaseHas('users', [
            'id'       => $this->user->id(),
            'world_id' => $worlds[0]->id(),
        ]);

        $this->auth();

        $response = $this->runApp('POST', '/api/worlds');
        $this->assertEquals(200, $response->getStatusCode());

        $worlds = $this->system->execute(new GetWorlds());
        $this->assertNotNull($worlds);
        $this->assertCount(1, $worlds);

        $this->assertDatabaseHas('users', [
            'id'       => $this->user->id(),
            'world_id' => $worlds[0]->id(),
        ]);
    }

    /** @test */
    public function that_on_enought_players_world_generates_map()
    {
        for ($i = 0; $i < 3; $i++) {
            $this->auth();
            $this->runApp('POST', '/api/worlds');
        }

        $this->assertDatabaseHas('worlds', [
            'status' => Status::MAP_GENERATION,
        ]);
    }
}
