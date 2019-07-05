<?php declare(strict_types=1);

namespace Tests\Unit\Map;

use App\Map\Infrastructure\MapSize;
use App\Map\Infrastructure\Position;
use Tests\BaseTestCase;

class MapSizeTest extends BaseTestCase
{
    /** @test */
    public function that_can_get_map_number_of_fields()
    {
        $size = new MapSize(16, 16);

        $this->assertEquals(256, $size->countFields());
    }

    /** @test */
    public function that_checks_if_position_is_out_of_border()
    {
        $size = new MapSize(8, 8);

        $this->assertTrue($size->outOfBorder(new Position(9, 8)));
        $this->assertTrue($size->outOfBorder(new Position(8, 9)));
        $this->assertTrue($size->outOfBorder(new Position(999, 888)));
    }

    /** @test */
    public function that_gets_center_position()
    {
        $size = new MapSize(7, 7);
        $position = new Position(4, 4);

        $this->assertTrue($position->is($size->getCenterPosition()));

        $size = new MapSize(8, 8);
        $position = new Position(4, 4);

        $this->assertTrue($position->is($size->getCenterPosition()));
    }
}
