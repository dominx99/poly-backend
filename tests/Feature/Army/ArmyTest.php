<?php declare(strict_types=1);

namespace Tests\Feature\Army;

use App\Army\Domain\ArmyUnit;
use App\Map\Domain\Map\Unit\Name;
use App\Map\Domain\Map\Unit\DisplayName;
use App\Map\Domain\Map\Unit\Cost;
use Tests\BaseTestCase;
use Tests\DatabaseTrait;
use App\Map\Domain\Map\Field;
use App\Map\Domain\Map;
use App\Map\Domain\Map\Field\X;
use App\Map\Domain\Map\Field\Y;
use App\Map\Domain\Map\MapObject;
use App\World\Domain\World;
use Ramsey\Uuid\Uuid;
use App\Map\Domain\Map\Unit\Power;
use App\Map\Domain\Map\Unit\Defense;
use App\System\Infrastructure\StatusCode;
use App\User\Domain\Resource;
use App\User\Domain\User;

final class ArmyTest extends BaseTestCase
{
    use DatabaseTrait;

    // TODO: Possible that should be removed
    /** @test */
    public function that_is_possible_to_create_army()
    {
        $this->auth();

        $worldId = (string) Uuid::uuid4();
        $mapId   = (string) Uuid::uuid4();
        $fieldId = (string) Uuid::uuid4();
        $unitId  = (string) Uuid::uuid4();

        $world      = new World($worldId);
        $map        = new Map($mapId);
        $field      = new Field((string) Uuid::uuid4(), new X(1), new Y(1));
        $fieldToGet = new Field($fieldId, new X(1), new Y(2));
        $armyUnit   = new ArmyUnit(
            $unitId,
            new Name('pikinier'),
            new DisplayName('pikinier'),
            new Cost(175),
            new Power(3),
            new Defense(3)
        );

        $resource = Resource::createDefault();
        $resource->setMap($map);
        $this->user->setResource($resource);

        $field->setUser(
            $this->entityManager->getRepository(User::class)->find($this->userId)
        );

        $map->addField($field);
        $map->addField($fieldToGet);
        $map->addUnit($armyUnit);
        $world->setMap($map);

        $this->entityManager->persist($resource);
        $this->entityManager->persist($this->user);
        $this->entityManager->persist($map);
        $this->entityManager->persist($world);
        $this->entityManager->flush();

        $this->assertDatabaseHas('worlds', ['id' => $worldId]);
        $this->assertDatabaseHas('fields', ['id' => $fieldId]);
        $this->assertDatabaseHas('maps', ['id' => $mapId]);

        $response = $this->runApp('POST', 'api/map/put', [
            'type'     => MapObject::ARMY_TYPE,
            'field_id' => $fieldId,
            'map_id'   => $mapId,
            'unit_id'  => $unitId,
        ]);

        $this->assertEquals(StatusCode::HTTP_OK, $response->getStatusCode());

        $this->assertDatabaseHas('map_objects', [
            'user_id'   => $this->userId,
            'field_id'  => $fieldId,
            'map_id'    => $mapId,
            'unit_id'   => $unitId,
            'unit_type' => MapObject::ARMY_TYPE,
        ]);

        $this->assertDatabaseHas('resources', [
            'user_id' => $this->userId,
            'gold'    => 125,
        ]);

        $this->assertDatabaseHas('fields', [
            'id'      => $fieldId,
            'user_id' => $this->userId,
        ]);
    }
}
