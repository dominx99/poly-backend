<?php declare(strict_types=1);

namespace App\Map\Infrastructure;

use App\Map\Contracts\Block;

final class Map
{
    /**
     * @var array
     */
    private $fields = [];

    /**
     * @var \App\Map\Infrastructure\MapSize
     */
    private $size;

    /**
     * @param \App\Map\Infrastructure\MapSize $size
     */
    public function __construct(MapSize $size)
    {
        $this->size = $size;
    }

    /**
     * @param \App\Map\Infrastructure\Position $position
     * @param \App\Map\Contracts\Block $block
     * @return void
     */
    public function put(Position $position, Block $block): void
    {
        $this->fields[] = [
            'position' => $position,
            'block'    => $block,
        ];
    }

    /**
     * @param \App\Map\Infrastructure\Position $position
     * @return bool
     */
    public function canPut(Position $position): bool
    {
        return !$this->hasFieldAtPosition($position) && !$this->size->outOfBorder($position);
    }

    /**
     * @param \App\Map\Infrastructure\Position $position
     * @return bool
     */
    public function canMove(Position $position): bool
    {
        return !$this->size->outOfBorder($position);
    }

    /**
     * @return array
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array_map(function ($field) {
            return [
                'x'    => $field['position']->getXPos(),
                'y'    => $field['position']->getYPos(),
                'type' => $field['block']->get(),
            ];
        }, $this->getFields());
    }

    /**
     * @param \App\Map\Infrastructure\Position $position
     * @return bool
     */
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
