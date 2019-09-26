<?php declare(strict_types = 1);

namespace App\Map\Domain\Map;

use App\Map\Domain\Map;
use \Doctrine\ORM\Mapping as ORM;
use App\User\Domain\User;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\Table(name="map_objects")
 * @ORM\DiscriminatorColumn(name="unit_type", type="string")
 * @ORM\DiscriminatorMap({"army" = "Army"})
 */
abstract class MapObject
{
    const ARMY_TYPE = 'army';

    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    protected $id;

    /**
     * @var \App\Map\Domain\Map\Field
     *
     * @ORM\OneToOne(targetEntity="\App\Map\Domain\Map\Field", inversedBy="mapObject")
     * @ORM\JoinColumn(name="field_id", referencedColumnName="id")
     */
    private $field;

    /**
     * @var \App\User\Domain\User
     *
     * @ORM\ManyToOne(targetEntity="\App\User\Domain\User", inversedBy="mapObjects")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var \App\Map\Domain\Map
     *
     * @ORM\ManyToOne(targetEntity="\App\Map\Domain\Map", inversedBy="mapObjects")
     * @ORM\JoinColumn(name="map_id", referencedColumnName="id")
     */
    private $map;

    /**
     * @var \App\Map\Domain\Map\Unit
     *
     * @ORM\ManyToOne(targetEntity="\App\Map\Domain\Map\Unit", inversedBy="mapObjects")
     * @ORM\JoinColumn(name="unit_id", referencedColumnName="id")
     */
    private $unit;

    /**
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @param \App\Map\Domain\Map\Field $field
     * @return void
     */
    public function setField(Field $field): void
    {
        if ($this->field === $field) {
            return;
        }

        $this->field = $field;
        $field->setMapObject($this);
    }

    /**
     * @param \App\User\Domain\User $user
     * @return void
     */
    public function setUser(User $user): void
    {
        if ($this->user === $user) {
            return;
        }

        $this->user = $user;
        $user->addMapObject($this);
    }

    /**
     * @param \App\Map\Domain\Map $map
     * @return void
     */
    public function setMap(Map $map): void
    {
        if ($this->map === $map) {
            return;
        }

        $this->map = $map;
        $map->addMapObjects($this);
    }

    /**
     * @param \App\Map\Domain\Map\Unit $unit
     * @return void
     */
    public function setUnit(Unit $unit): void
    {
        if ($this->unit === $unit) {
            return;
        }

        $this->unit = $unit;
        $unit->addMapObject($this);
    }
}
