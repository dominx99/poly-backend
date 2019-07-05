<?php declare(strict_types=1);

namespace App\Map\Infrastructure;

use App\Map\Infrastructure\Blocks\EmptyBlock;

final class MapGenerator
{
    // maps -> HasMany -> positions
    // positions | map_id object_id objectable
    // armies -> HasOne -> position
    // buildings -> HasOne ->position

    public function generate(MapSize $size)
    {
        $this->currentPosition = $size->getCenterPosition();
        $this->map = new Map($size);
        $this->moves = (int) round((1 / 2) * $size->countFields());

        while ($this->moves > 0) {
            $this->changePosition();
            $this->putRandomBlock();
        }

        return $this->map;
    }

    private function putRandomBlock(): void
    {
        $block = new EmptyBlock();

        if ($this->map->canPut($this->currentPosition)) {
            $this->map->put($this->currentPosition, $block);

            $this->moves--;
        }
    }

    private function changePosition(): void
    {
        $can = false;

        do {
            $clone = $this->currentPosition->clone();
            $clone->moveRandom();

            $can = $this->map->canMove($clone);
        } while (! $can);

        $this->currentPosition = $clone;
    }
}

