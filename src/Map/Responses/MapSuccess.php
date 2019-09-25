<?php declare(strict_types = 1);

namespace App\Map\Responses;

use App\System\Responses\Success;
use App\Map\Application\Query\FieldView;
use App\Map\Application\Query\MapView;
use App\Map\Application\Query\MapObjectView;

class MapSuccess extends Success
{
    /**
     * @param \App\Map\Application\Query\MapView $map
     */
    public function __construct(MapView $map)
    {
        parent::__construct([
            'map' => [
                'id'          => $map->id(),
                'world_id'    => $map->worldId(),
                'fields'      => array_map([$this, 'field'], $map->fields()),
                'map_objects' => array_map([$this, 'mapObject'], $map->mapObjects()),
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

    /**
     * @param \App\Map\Application\Query\MapObjectView $mapObject
     * @return array
     */
    public function mapObject(MapObjectView $mapObject): array
    {
        return [
            'id'        => $mapObject->id(),
            'field_id'  => $mapObject->fieldId(),
            'user_id'   => $mapObject->userId(),
            'unit_name' => $mapObject->unitName(),
        ];
    }
}
