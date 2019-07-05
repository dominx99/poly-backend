<?php declare(strict_types=1);

namespace Tests\Unit\Map\Infrastructure;

use Tests\BaseTestCase;
use App\Map\Infrastructure\Position;
use App\Map\Exceptions\UnexpectedPositionException;

class PositionTest extends BaseTestCase
{
    /** @test */
    public function that_gets_x_and_y_position()
    {
        $position = new Position(3, 10);

        $this->assertEquals(3, $position->getXPos());
        $this->assertEquals(10, $position->getYPos());
    }

    /**
     * @test
     * @dataProvider wrongPositions
     */
    public function cannot_declare_wrong_position($x, $y)
    {
        $this->expectException(UnexpectedPositionException::class);

        new Position($x, $y);
    }

    /** @test */
    public function that_compares_two_positions()
    {
        $position = new Position(5, 5);

        $this->assertTrue($position->is(new Position(5, 5)));

        $position = new Position(16, 7);

        $this->assertTrue($position->is(new Position(16, 7)));
    }

    /** @test */
    public function that_moves_in_random_directions()
    {
        $position = new Position(4, 4);
        $position->moveRandom();

        $this->assertFalse($position->is(new Position(4, 4)));
    }

    public function wrongPositions()
    {
        return [
            [-10, 5],
            [0, -1],
            [-1, -2],
        ];
    }
}
