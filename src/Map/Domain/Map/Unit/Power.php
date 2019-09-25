<?php declare(strict_types = 1);

namespace App\Map\Domain\Map\Unit;

use \Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class Power
{
    /**
     * @var int
     *
     * @ORM\Column(name="power", type="integer", nullable=false, unique=true)
     */
    private $power;

    /**
     * @param int $power
     */
    public function __construct(int $power)
    {
        $this->power = $power;
    }
}
