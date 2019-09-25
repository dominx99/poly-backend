<?php declare(strict_types=1);

namespace App\Map\Application\Query;

final class MapObjectView
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $fieldId;

    /**
     * @var string
     */
    private $userId;

    /**
     * @var string
     */
    private $unitName;

    /**
     * @param string $id
     * @param string $worldId
     */
    public function __construct(string $id, string $fieldId, string $userId, string $unitName)
    {
        $this->id       = $id;
        $this->fieldId  = $fieldId;
        $this->userId   = $userId;
        $this->unitName = $unitName;
    }

    /**
     * @param array $mapObject
     * @return \App\Map\Application\Query\MapObjectView
     */
    public function createFromDatabase(array $mapObject): MapObjectView
    {
        return new static(
            $mapObject['id'],
            $mapObject['field_id'],
            $mapObject['user_id'],
            $mapObject['name'],
        );
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
    public function unitName(): string
    {
        return $this->unitName;
    }
}
