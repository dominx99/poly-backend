<?php declare(strict_types = 1);

namespace App\Map\Domain\Map\Unit;

use \Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class Defense
{
    /**
     * @var int
     *
     * @ORM\Column(name="defense", type="integer", nullable=false, unique=true)
     */
    private $defense;

    /**
     * @param int $defense
     */
    public function __construct(int $defense)
    {
        $this->defense = $defense;
    }
}
