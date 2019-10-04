<?php declare(strict_types=1);

namespace App\Map\Application\Query;

use App\Unit\Application\Views\UnitView;
use App\System\ValueObjects\DateTime;

final class MapObjectView
{
    /** @var string */
    private $id;

    /** @var string */
    private $fieldId;

    /** @var string */
    private $userId;

    /** @var string */
    private $mapId;

    /** @var \DateTime */
    private $earnedAt;

    /** @var \App\Unit\Application\Views\UnitView */
    private $unit;

    /**
     * @param string $id
     * @param string $fieldId
     * @param string $userId
     * @param string $mapId
     */
    public function __construct(
        string $id,
        string $fieldId,
        string $userId,
        string $mapId,
        $earnedAt
    ) {
        $this->id         = $id;
        $this->fieldId    = $fieldId;
        $this->userId     = $userId;
        $this->mapId      = $mapId;
        $this->earnedAt   = $earnedAt;
    }

    /**
     * @param array $mapObject
     * @return \App\Map\Application\Query\MapObjectView
     */
    public static function createFromDatabase(array $mapObject): MapObjectView
    {
        return new static(
            $mapObject['id'],
            $mapObject['field_id'],
            $mapObject['user_id'],
            $mapObject['map_id'],
            $mapObject['earned_at']
        );
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id'        => $this->id(),
            'field_id'  => $this->fieldId(),
            'user_id'   => $this->userId(),
            'map_id'    => $this->mapId(),
        ];
    }

    /**
     * @param \App\Unit\Application\Views\UnitView $unit
     * @return void
     */
    public function setUnit(UnitView $unit): void
    {
        $this->unit = $unit;
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function fieldId(): string
    {
        return $this->fieldId;
    }

    /**
     * @return string
     */
    public function userId(): string
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function mapId(): string
    {
        return $this->mapId;
    }

    /**
     * @return \App\Unit\Application\Views\UnitView
     */
    public function unit(): UnitView
    {
        return $this->unit;
    }

    /**
     * @return int
     */
    public function earnedAtInSeconds()
    {
        $earnedAt = DateTime::createFromFormat('Y-m-d H:i:s', $this->earnedAt);
        $now      = time();

        return $now - $earnedAt->getTimestamp();
    }
}
