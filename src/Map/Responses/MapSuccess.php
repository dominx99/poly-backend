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
                'id'         => $map->id(),
                'world_id'   => $map->worldId(),
                'fields'     => array_map([$this, 'field'], $map->fields()),
                /* 'armies'     => array_map([$this, 'army'], $map->armies()), */
                /* 'factories'  => array_map([$this, 'factory'], $map->factories()), */
                /* 'structures' => array_map([$this, 'structure'], $map->structures()), */
            ],
        ]);
    }

    /**
     * @param \App\Map\Application\Query\FieldView $field
     * @return array
     */
    public function field(FieldView $field): array
    {
        return [
            'id'      => $field->id(),
            'x'       => $field->x(),
            'y'       => $field->y(),
            'user_id' => $field->userId(),
        ];
    }
}
