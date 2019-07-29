<?php declare(strict_types = 1);

namespace App\Map\Responses;

use App\System\Responses\Success;
use App\Map\Application\Query\FieldView;
use App\Map\Application\Query\MapView;

class MapSuccess extends Success
{
    /**
     * @param \App\Map\Application\Query\MapView $map
     */
    public function __construct(MapView $map)
    {
        parent::__construct([
            'map' => [
                'world_id' => $map->worldId(),
                'fields'   => array_map([$this, 'fields'], $map->fields()),
            ],
        ]);
    }

    /**
     * @param \App\Map\Application\Query\FieldView $field
     * @return array
     */
    public function fields(FieldView $field): array
    {
        return [
            'x'       => $field->x(),
            'y'       => $field->y(),
            'type'    => $field->type(),
            'user_id' => $field->userId(),
        ];
    }
}
