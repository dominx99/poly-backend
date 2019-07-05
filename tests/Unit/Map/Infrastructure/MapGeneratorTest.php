<?php

namespace Tests\Unit\Map\Infrastructure;

use Tests\BaseTestCase;
use App\Map\Infrastructure\MapSize;
use App\Map\Infrastructure\MapGenerator;

class MapGeneratorTest extends BaseTestCase
{
    /** @test */
    public function that_elements_are_correct()
    {
        $generator = new MapGenerator();

        $map = $generator->generate(new MapSize(10, 10));

        $elementsOnMap = (int) round(1 / 2 * 10 * 10);

        $this->assertEquals($elementsOnMap, count($map->getFields()));
    }
}
