<?php declare(strict_types=1);

namespace Tests\Feature\Army;

use App\Army\Domain\BaseArmy;
use App\Army\Domain\BaseArmy\Cost;
use App\Army\Domain\BaseArmy\DisplayName;
use App\Army\Domain\BaseArmy\Name;
use Tests\BaseTestCase;
use Tests\DatabaseTrait;
use App\Map\Domain\Map\Field;
use App\Map\Domain\Map;
use App\Map\Domain\Map\Army;
use App\Map\Domain\Map\Field\X;
use App\Map\Domain\Map\Field\Y;
use App\Map\Domain\Map\MapObject;
use App\World\Domain\World;
use Ramsey\Uuid\Uuid;

final class ArmyTest extends BaseTestCase
{
    use DatabaseTrait;

    // TODO: Possible that should be removed
    /** @test */
    public function that_is_possible_to_create_army()
    {
        for ($i = 0; $i < 3; $i++) {
            $this->auth();
            $this->runApp('POST', '/api/worlds');
        }

        $worldId = (string) Uuid::uuid4();
        $mapId = (string) Uuid::uuid4();
        $fieldId = (string) Uuid::uuid4();
        $placableId = (string) Uuid::uuid4();
        $placableId = (string) Uuid::uuid4();

        $world = new World($worldId);
        $map = new Map($mapId);
        $field = new Field($fieldId, new X(1), new Y(1));
        $baseArmy = new BaseArmy($placableId, new Name('pikinier'), new DisplayName('pikinier'), new Cost(300));

        $map->addField($field);
        $map->addBaseArmy($baseArmy);
        $world->setMap($map);

        $this->entityManager->persist($map);
        $this->entityManager->persist($world);
        $this->entityManager->flush();

        $this->assertDatabaseHas('worlds', ['id' => $worldId]);
        $this->assertDatabaseHas('fields', ['id' => $fieldId]);
        $this->assertDatabaseHas('maps', ['id' => $mapId]);

        $response = $this->runApp('POST', 'api/map/put', [
            'type'        => MapObject::ARMY_TYPE,
            'field_id'    => $fieldId,
            'map_id'      => $mapId,
            'placable_id' => $placableId,
        ]);

        var_dump((string) $response->getBody());die();

        $this->assertDatabaseHas('map_objects', [
            'user_id'       => $this->userId,
            'field_id'      => $fieldId,
            'map_id'        => $mapId,
            'placable_id'   => $placableId,
            'placable_type' => MapObject::ARMY_TYPE,
        ]);
    }
}
