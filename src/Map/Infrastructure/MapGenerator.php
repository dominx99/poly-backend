<?php declare(strict_types=1);

namespace App\Map\Infrastructure;

use App\Map\Infrastructure\Blocks\EmptyBlock;

final class MapGenerator
{
    /**
     * @var \App\Map\Infrastructure\Position
     */
    private $currentPosition;

    /**
     * @var \App\Map\Infrastructure\Map
     */
    private $map;

    /**
     * @var int
     */
    private $moves;

    /**
     * @param \App\Map\Infrastructure\MapSize $size
     * @return \App\Map\Infrastructure\Map
     */
    public function generate(MapSize $size): Map
    {
        $this->currentPosition = $size->getCenterPosition();
        $this->map             = new Map($size);
        $this->moves           = (int) round((1 / 2) * $size->countFields());

        while ($this->moves > 0) {
            $this->changePosition();
            $this->putRandomBlock();
        }

        return $this->map;
    }

    /**
     * @return void
     */
    private function putRandomBlock(): void
    {
        $block = new EmptyBlock();

        if ($this->map->canPut($this->currentPosition)) {
            $this->map->put($this->currentPosition, $block);

            $this->moves--;
        }
    }

    /**
     * @return void
     */
    private function changePosition(): void
    {
        do {
            $clone = $this->currentPosition->clone();
            $clone->moveRandom();

            $can = $this->map->canMove($clone);
        } while (! $can);

        $this->currentPosition = $clone;
    }
}
