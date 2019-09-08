<?php declare(strict_types = 1);

namespace App\Map\Domain\Map;

use \Doctrine\ORM\Mapping as ORM;
use App\Map\Domain\Map;
use App\Map\Domain\Map\Field\X;
use App\Map\Domain\Map\Field\Y;

/**
 * @ORM\Entity
 * @ORM\Table(name="fields")
 */
class Field
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private $id;

    /**
     * @var \App\Map\Domain\Map\Field\X
     *
     * @ORM\Embedded(class="\App\Map\Domain\Map\Field\X", columnPrefix=false)
     */
    private $x;

    /**
     * @var \App\Map\Domain\Map\Field\Y
     *
     * @ORM\Embedded(class="\App\Map\Domain\Map\Field\Y", columnPrefix=false)
     */
    private $y;

    /**
     * @var \App\Map\Domain\Map
     *
     * @ORM\ManyToOne(targetEntity="\App\Map\Domain\Map", inversedBy="fields")
     * @ORM\JoinColumn(name="map_id", referencedColumnName="id")
     */
    private $map;

    /**
     * @var \App\User\Domain\User
     *
     * @ORM\OneToOne(targetEntity="\App\User\Domain\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @param string $id
     * @param \App\Map\Domain\Map\Field\X $x
     * @param \App\Map\Domain\Map\Field\Y $y
     */
    public function __construct(string $id, X $x, Y $y)
    {
        $this->id   = $id;
        $this->x    = $x;
        $this->y    = $y;
    }

    /**
     * @param \App\Map\Domain\Map $map
     * @return void
     */
    public function addMap(Map $map): void
    {
        if ($this->map === $map) {
            return;
        }

        $this->map = $map;
        $map->addField($this);
    }
}
