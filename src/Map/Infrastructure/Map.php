<?php declare(strict_types=1);

namespace App\Map\Infrastructure;

use App\Map\Infrastructure\Blocks\Block;

final class Map
{
    private $fields = [];

    private $size;

    public function __construct(MapSize $size)
    {
        $this->size = $size;
    }

    public function put(Position $position, Block $block): void
    {
        $this->fields[] = [
            'position' => $position,
            'block'    => $block,
        ];
    }

    public function canPut(Position $position): bool
    {
        return !$this->hasFieldAtPosition($position) && !$this->size->outOfBorder($position);
    }

    public function canMove(Position $position): bool
    {
        return !$this->size->outOfBorder($position);
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    private function hasFieldAtPosition(Position $position): bool
    {
        $result = false;

        foreach (array_column($this->fields, 'position') as $fieldPosition) {
            if ($position->is($fieldPosition)) {
                $result = true;
            }
        }

        return $result;
    }
}
