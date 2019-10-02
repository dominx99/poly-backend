<?php declare(strict_types=1);

namespace App\Map\Application\Events;

use App\System\Infrastructure\Event\Event;

final class CollectedMoneyFromMapObjectsByUser implements Event
{
    /** @var string */
    private $mapId;

    /** @var string */
    private $userId;

    /**
     * @param string $mapId
     * @param string $userId
     */
    public function __construct(string $mapId, string $userId)
    {
        $this->mapId  = $mapId;
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function mapId(): string
    {
        return $this->mapId;
    }

    /**
     * @return string
     */
    public function userId(): string
    {
        return $this->userId;
    }
}
