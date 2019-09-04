<?php declare(strict_types = 1);

namespace App\Army\Domain\BaseArmy;

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
        $this->cost = $cost;
    }
}
