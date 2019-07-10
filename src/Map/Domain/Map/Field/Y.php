<?php declare(strict_types = 1);

namespace App\Map\Domain\Map\Field;

use \Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class Y
{
    /**
     * @var int
     *
     * @ORM\Column(name="y", type="integer", nullable=true)
     */
    private $y;

    /**
     * @param int $y
     */
    public function __construct(int $y)
    {
        $this->y = $y;
    }
}
