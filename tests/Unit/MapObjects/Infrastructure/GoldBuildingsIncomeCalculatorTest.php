<?php declare(strict_types=1);

namespace Tests\Unit\MapObjects\Infrastructure;

use App\Army\Domain\ArmyUnit;
use Tests\BaseTestCase;
use App\Map\Application\Query\MapObjectView;
use App\Resource\Infrastructure\GoldMapObjectsIncomeCalculator;
use App\Unit\Application\Views\UnitView;
use Ramsey\Uuid\Uuid;
use App\Building\Domain\BuildingUnit;

final class GoldMapObjectsIncomeCalculatorTest extends BaseTestCase
{
    /** @test */
    public function that_calculates_well()
    {
        $calculator = new GoldMapObjectsIncomeCalculator();

        $baseData = ['user_id' => 'test', 'map_id' => 'test'];

        $now = 10000;

        eval("namespace App\Map\Application\Query; function time() { return {$now}; }");

        $before = new \DateTime('now');
        $before->setTimestamp($now - 15);

        $armyUnit = UnitView::createFromDatabase(
            array_merge(ArmyUnit::DEFAULT_ARMIES[0], ['id' => (string) Uuid::uuid4()])
        );
        $buildingUnit = UnitView::createFromDatabase(
            array_merge(BuildingUnit::DEFAULT_BUILDINGS[0], ['id' => (string) Uuid::uuid4()])
        );

        $mapObject = MapObjectView::createFromDatabase(array_merge($baseData, [
            'id'        => (string) Uuid::uuid4(),
            'field_id'  => 'test1',
            'earned_at' => $before->format('Y-m-d h:i:s'),
        ]));
        $mapObject->setUnit($armyUnit);
        $mapObjects[] = $mapObject;

        $mapObject = MapObjectView::createFromDatabase(array_merge($baseData, [
            'id'        => (string) Uuid::uuid4(),
            'field_id'  => 'test2',
            'earned_at' => $before->format('Y-m-d h:i:s'),
        ]));
        $mapObject->setUnit($buildingUnit);
        $mapObjects[] = $mapObject;

        $result = $calculator->from($mapObjects)
            ->withConversion(2)
            ->calculate();

        $this->assertSame(120, $result);
    }
}
