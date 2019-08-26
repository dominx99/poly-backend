<?php declare(strict_types = 1);

namespace App\World\Application\Events;

use App\System\Infrastructure\Event\Event;

class WorldReady implements Event
{
    /**
     * @var string
     */
    private $worldId;

    /**
     * @param string $worldId
     */
    public function __construct(string $worldId)
    {
        $this->worldId = $worldId;
    }

    /**
     * @return string
     */
    public function worldId(): string
    {
        return $this->worldId;
    }
}
