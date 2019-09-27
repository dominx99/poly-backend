<?php declare(strict_types = 1);

namespace App\Map\Application\Events;

use App\System\Infrastructure\Event\Event;

class PlacedMapObject implements Event
{
    /** @var string */
    private $mapObjectId;

    /**
     * @param string $mapObjectId
     */
    public function __construct(string $mapObjectId)
    {
        $this->mapObjectId = $mapObjectId;
    }

    /**
     * @return string
     */
    public function mapObjectId(): string
    {
        return $this->mapObjectId;
    }
}
