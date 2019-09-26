<?php declare(strict_types=1);

namespace Tests\Unit\Army\Application\Handlers;

use Tests\BaseTestCase;
use Tests\DatabaseTrait;
use App\Map\Domain\Map;
use App\Map\Application\Events\MapGenerated;
use Ramsey\Uuid\Uuid;
use App\Army\Domain\ArmyUnit;
use App\World\Domain\World\Status;
use App\World\Domain\World;
use App\Army\Application\Handlers\AssignDefaultArmyUnitsHandler;
use App\System\System;

class AssignDefaultArmyUnitsHandlerTest extends BaseTestCase
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

        $assignArmiesHandler = new AssignDefaultArmyUnitsHandler($this->container->get(System::class));
        $assignArmiesHandler->handle(new MapGenerated($mapId));

        foreach (ArmyUnit::DEFAULT_ARMIES as $army) {
            $this->assertDatabaseHas('units', [
                'map_id'       => $mapId,
                'name'         => $army['name'],
                'display_name' => $army['display_name'],
                'cost'         => $army['cost'],
            ]);
        }
    }
}
