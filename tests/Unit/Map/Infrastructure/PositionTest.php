<?php declare(strict_types=1);

namespace Tests\Unit\Map\Infrastructure;

use Tests\BaseTestCase;
use App\Map\Infrastructure\Position;

class PositionTest extends BaseTestCase
{
    /** @test */
    public function that_gets_x_and_y_position()
    {
        $position = new Position(3, 10);

        $this->assertEquals(3, $position->getXPos());
        $this->assertEquals(10, $position->getYPos());
    }

    public function cannot_declare_wrong_position()
    {
        $position = new Position(-10, 2);

        $this->assertEquals(-10, $position->getXPos());
    }
}
