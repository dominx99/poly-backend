<?php declare(strict_types = 1);

namespace App\User\Domain\User;

use \Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class Color
{
    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", nullable=false, unique=true)
     */
    private $color;

    /**
     * @param string $color
     */
    public function __construct(string $color)
    {
        $this->color = $color;
    }
}
