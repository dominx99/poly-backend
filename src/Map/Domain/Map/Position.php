<?php declare(strict_types = 1);

namespace App\Map\Domain\Map;

use \Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"army" = "Army", "field" = "field"})
 */
class Position
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    protected $id;

    /**
     * @var int
     *
     * @ORM\Column(name="password", type="int")
     */
    protected $x;

    /**
     * @var int
     *
     * @ORM\Column(name="password", type="int")
     */
    protected $y;

    /**
     * @param int $x
     * @param int $y
     */
    public function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }
}
