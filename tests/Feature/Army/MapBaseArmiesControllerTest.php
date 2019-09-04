<?php declare(strict_types=1);

namespace Tests\Feature\Army;

use App\World\Application\GetWorlds;
use Tests\BaseTestCase;
use App\Army\Domain\BaseArmy;
use Tests\DatabaseTrait;
use App\Map\Contracts\MapQueryRepository;

final class MapBaseArmiesControllerTest extends BaseTestCase
{
    use DatabaseTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->maps = $this->container->get(MapQueryRepository::class);
    }

    /** @test */
    public function that_controller_returns_good_amies()
    {
        for ($i = 0; $i < 3; $i++) {
            $this->auth();
            $this->runApp('POST', '/api/worlds');
        }

        $world = $this->system->execute(new GetWorlds())[0];
        $map   = $this->maps->findByWorld($world->id());

        $response = $this->runApp('GET', 'api/map/' . $map->id() . '/base-armies');
        $response = json_decode((string) $response->getBody(), true);

        $this->assertArrayHasKey('data', $response);
        $this->assertCount(count(BaseArmy::DEFAULT_ARMIES), $response['data']);
    }
}
