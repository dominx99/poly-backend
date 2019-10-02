<?php declare(strict_types = 1);

namespace App\Unit\Application\Views;

class UnitView
{
    /** @var string */
    private $id;

    /** @var string */
    private $name;

    /** @var string */
    private $displayName;

    /** @var int */
    private $cost;

    /** @var int */
    private $power;

    /** @var int */
    private $defense;

    /** @var int */
    private $earningPoints;

    /**
     * @param string $id
     * @param string $name
     * @param string $displayName
     * @param int $cost
     * @param int $power
     * @param int $defense
     */
    public function __construct(
        string $id,
        string $name,
        string $displayName,
        int $cost,
        int $power,
        int $defense,
        int $earningPoints
    ) {
        $this->id            = $id;
        $this->name          = $name;
        $this->displayName   = $displayName;
        $this->cost          = $cost;
        $this->power         = $power;
        $this->defense       = $defense;
        $this->earningPoints = $earningPoints;
    }

    /**
     * @param  array $unit
     * @return self
     */
    public static function createFromDatabase(array $unit): self
    {
        return new static(
            $unit['id'],
            $unit['name'],
            $unit['display_name'],
            (int) $unit['cost'],
            (int) $unit['power'],
            (int) $unit['defense'],
            (int) $unit['earning_points']
        );
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id'           => $this->id(),
            'name'         => $this->name(),
            'display_name' => $this->displayName(),
            'cost'         => $this->cost(),
            'power'        => $this->power(),
            'defense'      => $this->defense(),
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
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function displayName(): string
    {
        return $this->displayName;
    }

    /**
     * @return int
     */
    public function cost(): int
    {
        return $this->cost;
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

    /**
     * @return int
     */
    public function earningPoints(): int
    {
        return $this->earningPoints;
    }
}
