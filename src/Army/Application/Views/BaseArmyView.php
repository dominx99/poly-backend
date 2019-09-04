<?php declare(strict_types = 1);

namespace App\Army\Application\Views;

class BaseArmyView
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $displayName;

    /**
     * @var int
     */
    private $cost;

    /**
     * @param string $id
     * @param string $name
     * @param string $displayName
     * @param int $cost
     */
    public function __construct(string $id, string $name, string $displayName, int $cost)
    {
        $this->id          = $id;
        $this->name        = $name;
        $this->displayName = $displayName;
        $this->cost        = $cost;
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
     * @param  array $baseArmy
     * @return self
     */
    public static function createFromDatabase(array $baseArmy): self
    {
        return new static(
            $baseArmy['id'],
            $baseArmy['name'],
            $baseArmy['display_name'],
            (int) $baseArmy['cost']
        );
    }
}
