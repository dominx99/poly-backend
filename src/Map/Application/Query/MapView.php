<?php declare(strict_types = 1);

namespace App\Map\Application\Query;

class MapView
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $worldId;

    /**
     * @var array
     */
    private $fields;

    /**
     * @param string $id
     * @param string $worldId
     */
    public function __construct(string $id, string $worldId)
    {
        $this->id      = $id;
        $this->worldId = $worldId;
        $this->fields  = [];
    }

    /**
     * @param array $map
     * @return \App\Map\Application\Query\MapView
     */
    public function createFromDatabase(array $map): MapView
    {
        return new static(
            $map['id'],
            $map['world_id']
        );
    }

    /**
     * @param array $fields
     * @return void
     */
    public function setFields(array $fields): void
    {
        $this->fields = $fields;
    }

    /**
     * @param array $mapObjects
     * @return array
     */
    public function setMapObjects(array $mapObjects): void
    {
        $this->mapObjects = $mapObjects;
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
    public function worldId(): string
    {
        return $this->worldId;
    }

    /**
     * @return array
     */
    public function fields(): array
    {
        return $this->fields;
    }

    /**
     * @return array
     */
    public function mapObjects(): array
    {
        return $this->mapObjects;
    }
}
