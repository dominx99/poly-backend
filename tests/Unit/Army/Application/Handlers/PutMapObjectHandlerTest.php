<?php declare(strict_types=1);

namespace Tests\Unit\Army\Application\Handlers;

use Tests\BaseTestCase;
use App\Map\Domain\Map\MapObject;

final class PutMapObjectHandlerTest extends BaseTestCase
{
    /** @test */
    public function that_checks_if_its_possible_to_put_map_object()
    {
        $possible = $this->system->execute(new PossibleToPutMapObject([
            'power' => 5
        ]));

        $this->assertTrue($possible);
    }

    /** @test */
    public function that_puts_map_object()
    {
        $this->markTestSkipped();

        $this->system->handle(new PutMapObject([
            'type'        => MapObject::ARMY_TYPE,
            'field'       => (string) Uuid::uuid4(),
            'map'         => (string) Uuid::uuid4(),
            'placable_id' => (string) Uuid::uuid4(),
        ]));
    }
}
