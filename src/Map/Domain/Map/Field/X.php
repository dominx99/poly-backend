<?php declare(strict_types = 1);

namespace App\Map\Domain\Map\Field;

use \Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class X
{
    /**
     * @var int
     *
     p @ORM\Column(name="x", type="integer", nullable=true)
     */
    private $x;

    /**
     * @param int $x
     */
    public function __construct(int $x)
    {
        $this->x = $x;
    }
}
