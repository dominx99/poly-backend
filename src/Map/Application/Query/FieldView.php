<?php declare(strict_types = 1);

namespace App\Map\Application\Query;

class FieldView
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $mapId;

    /**
     * @var string|null
     */
    private $userId;

    /**
     * @var int
     */
    private $x;

    /**
     * @var int
     */
    private $y;

    /**
     * @param string $id
     * @param string $mapId
     * @param string $userId
     * @param int $x
     * @param int $y
     */
    public function __construct(string $id, string $mapId, string $userId = null, int $x, int $y)
    {
        $this->id     = $id;
        $this->mapId  = $mapId;
        $this->userId = $userId;
        $this->x      = $x;
        $this->y      = $y;
    }

    /**
     * @param array $field
     * @return \App\Map\Application\Query\FieldView
     */
    public function createFromDatabase(array $field): FieldView
    {
        return new static(
            $field['id'],
            $field['map_id'],
            $field['user_id'],
            (int) $field['x'],
            (int) $field['y'],
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
    public function mapId(): string
    {
        return $this->mapId;
    }

    /**
     * @return string|null
     */
    public function userId()
    {
        return $this->userId;
    }

    /**
     * @return int
     */
    public function x(): int
    {
        return $this->x;
    }

    /**
     * @return int
     */
    public function y(): int
    {
        return $this->y;
    }
}
