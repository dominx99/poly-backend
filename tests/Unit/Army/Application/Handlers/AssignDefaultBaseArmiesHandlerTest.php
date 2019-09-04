<?php declare(strict_types=1);

namespace Tests\Unit\Army\Application\Handlers;

use Tests\BaseTestCase;
use Tests\DatabaseTrait;
use App\Map\Domain\Map;
use App\Map\Application\Events\MapGenerated;
use Ramsey\Uuid\Uuid;
use App\Army\Domain\BaseArmy;
use App\World\Domain\World\Status;
use App\World\Domain\World;
use App\Army\Application\Handlers\AssignDefaultBaseArmiesHandler;
use App\System\System;

class AssignDefaultBaseArmiesHandlerTest extends BaseTestCase
{
    use DatabaseTrait;

    /** @test */
    public function that_assigns_armies()
    {
        $mapId = (string) Uuid::uuid4();
        $map   = new Map($mapId);
        $world = new World((string) Uuid::uuid4(), new Status(Status::CREATED));
        $map->addWorld($world);

        $this->entityManager->persist($world);
        $this->entityManager->persist($map);
        $this->entityManager->flush();

        $assignArmiesHandler = new AssignDefaultBaseArmiesHandler($this->container->get(System::class));
        $assignArmiesHandler->handle(new MapGenerated($mapId));

        foreach (BaseArmy::DEFAULT_ARMIES as $army) {
            $this->assertDatabaseHas('base_armies', [
                'map_id'       => $mapId,
                'name'         => $army['name'],
                'display_name' => $army['display_name'],
                'cost'         => $army['cost'],
            ]);
        }
    }
}
