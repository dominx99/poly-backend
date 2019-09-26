<?php declare(strict_types = 1);

namespace App\Map\Domain\Map;

use App\Map\Domain\Map;
use \Doctrine\ORM\Mapping as ORM;
use App\Map\Domain\Map\Unit\Name;
use App\Map\Domain\Map\Unit\DisplayName;
use App\Map\Domain\Map\Unit\Cost;
use App\Map\Domain\Map\Unit\Power;
use Doctrine\Common\Collections\ArrayCollection;
use App\Map\Domain\Map\Unit\Defense;
use App\System\Contracts\Buyable;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\Table(name="units")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"army" = "\App\Army\Domain\ArmyUnit"})
 */
abstract class Unit implements Buyable
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    protected $id;

    /**
     * @var \App\Map\Domain\Map
     *
     * @ORM\ManyToOne(targetEntity="\App\Map\Domain\Map", inversedBy="units")
     * @ORM\JoinColumn(name="map_id", referencedColumnName="id")
     */
    protected $map;

    /**
     * @var \App\Map\Domain\Map\Unit\Name
     *
     * @ORM\Embedded(class="\App\Map\Domain\Map\Unit\Name", columnPrefix=false)
     */
    protected $name;

    /**
     * @var \App\Map\Domain\Map\Unit\DisplayName
     *
     * @ORM\Embedded(class="\App\Map\Domain\Map\Unit\DisplayName", columnPrefix=false)
     */
    protected $displayName;

    /**
     * @var \App\Map\Domain\Map\Unit\Cost
     *
     * @ORM\Embedded(class="\App\Map\Domain\Map\Unit\Cost", columnPrefix=false)
     */
    protected $cost;

    /**
     * @var \App\Map\Domain\Map\Unit\Power
     *
     * @ORM\Embedded(class="\App\Map\Domain\Map\Unit\Power", columnPrefix=false)
     */
    protected $power;

    /**
     * @var \App\Map\Domain\Map\Unit\Defense
     *
     * @ORM\Embedded(class="\App\Map\Domain\Map\Unit\Defense", columnPrefix=false)
     */
    protected $defense;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection|\App\Map\Domain\Map\MapObject[]
     *
     * @ORM\OneToMany(targetEntity="\App\Map\Domain\Map\MapObject", mappedBy="unit", cascade={"persist"})
     */
    protected $mapObjects;

    /**
     * @param string $id
     * @param \App\Map\Domain\Map\Unit\Name $name
     * @param \App\Map\Domain\Map\Unit\DisplayName $displayName
     * @param \App\Map\Domain\Map\Unit\Cost $cost
     * @param \App\Map\Domain\Map\Unit\Power $power
     * @param \App\Map\Domain\Map\Unit\Defense $defense
     */
    public function __construct(
        string $id,
        Name $name,
        DisplayName $displayName,
        Cost $cost,
        Power $power,
        Defense $defense
    ) {
        $this->id          = $id;
        $this->name        = $name;
        $this->displayName = $displayName;
        $this->cost        = $cost;
        $this->power       = $power;
        $this->defense     = $defense;
        $this->mapObjects  = new ArrayCollection();
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
        $map->addUnit($this);
    }

    /**
     * @param \App\Map\Domain\Map\MapObject $mapObject
     * @return void
     */
    public function addMapObject(MapObject $mapObject): void
    {
        if ($this->mapObjects->contains($mapObject)) {
            return;
        }

        $this->mapObjects->add($mapObject);
        $mapObject->setUnit($this);
    }

    /**
     * @return int
     */
    public function getCost(): int
    {
        return $this->cost->getCost();
    }
}
