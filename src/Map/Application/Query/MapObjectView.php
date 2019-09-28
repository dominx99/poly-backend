<?php declare(strict_types=1);

namespace App\Map\Application\Query;

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

	/** @var string */
    private $unitName;

	/** @var int */
	private $power;

	/** @var int */
	private $defense;

    /**
     * @param string $id
     * @param string $fieldId
     * @param string $userId
     * @param string $mapId
     * @param string $unitName
     */
    public function __construct(
        string $id,
        string $fieldId,
        string $userId,
        string $mapId,
        string $unitName,
        int $power,
        int $defense
    ) {
        $this->id       = $id;
        $this->fieldId  = $fieldId;
        $this->userId   = $userId;
        $this->mapId    = $mapId;
        $this->unitName = $unitName;
		$this->power = $power;
		$this->defense = $defense;
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
            $mapObject['name'],
            (int) $mapObject['power'],
            (int) $mapObject['defense']
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
            'unit_name' => $this->unitName(),
            'power'     => $this->power(),
            'defense'   => $this->defense(),
        ];
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

    /**
     * @return string
     */
    public function mapId(): string
    {
        return $this->mapId;
    }

	/**
	 * @return int
	 */
	public function power(): int
	{
		return $this->power;
	}

	/**
	 * @return int
	 */
	public function defense(): int
	{
		return $this->defense;
	}
}
