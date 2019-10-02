<?php declare(strict_types = 1);

namespace App\Map\Application\Notifications;

use App\System\Contracts\SocketEvent;
use App\Map\Application\Query\MapObjectView;

class PlacedMapObjectNotification implements SocketEvent
{
    /** @var \App\Map\Application\Query\MapObjectView */
    private $mapObject;

    /**
     * @param \App\Map\Application\Query\MapObjectView $mapObject
     */
    public function __construct(MapObjectView $mapObject)
    {
        $this->mapObject = $mapObject;
    }

    /**
     * @return string
     */
    public function channel(): string
    {
        return $this->mapObject->mapId();
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return 'map.map-object.placed';
    }

    /**
     * @return array
     */
    public function data(): array
    {
        return [
            'id'        => $this->mapObject->id(),
            'field_id'  => $this->mapObject->fieldId(),
            'user_id'   => $this->mapObject->userId(),
            'map_id'    => $this->mapObject->mapId(),
            'unit_name' => $this->mapObject->unit()->name(),
            'defense'   => $this->mapObject->unit()->defense(),
            'power'     => $this->mapObject->unit()->power(),
        ];
    }
}
