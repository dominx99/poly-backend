<?php declare(strict_types = 1);

namespace App\Map\Domain\Map\Unit;

use \Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class Cost
{
    /**
     * @var int
     *
     * @ORM\Column(name="cost", type="integer", nullable=false, unique=true)
     */
    private $cost;

    /**
     * @param int $cost
     */
    public function __construct(int $cost)
    {
        if ($cost < 0) {
            throw new \Exception('Cost can not be less than 0.');
        }

        $this->cost = $cost;
    }

    /**
     * @return int
     */
    public function getCost(): int
    {
        return $this->cost;
    }
}
