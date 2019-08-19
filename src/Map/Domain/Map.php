<?php declare(strict_types = 1);

namespace App\Map\Domain;

use \Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\World\Domain\World;
use App\Map\Domain\Map\Field;
use App\User\Domain\Resource;

/**
 * @ORM\Entity
 * @ORM\Table(name="maps")
 */
class Map
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private $id;

    /**
     * @var \App\World\Domain\World
     *
     * @ORM\OneToOne(targetEntity="\App\World\Domain\World", inversedBy="map")
     */
    private $world;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection|\App\Map\Domain\Map\Field[]
     *
     * @ORM\OneToMany(targetEntity="\App\Map\Domain\Map\Field", mappedBy="map", cascade={"persist"})
     */
    private $fields;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection|\App\User\Domain\Resource[]
     *
     * @ORM\OneToMany(targetEntity="\App\User\Domain\Resource", mappedBy="map", cascade={"persist"})
     */
    private $resources;

    /**
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id        = $id;
        $this->fields    = new ArrayCollection();
        $this->resources = new ArrayCollection();
    }

    /**
     * @param \App\World\Domain\World $world
     * @return void
     */
    public function addWorld(World $world): void
    {
        if ($this->world === $world) {
            return;
        }

        $this->world = $world;
        $world->addMap($this);
    }

    /**
     * @param \App\Map\Domain\Map\Field $field
     * @return void
     */
    public function addField(Field $field): void
    {
        if ($this->fields->contains($field)) {
            return;
        }

        $this->fields->add($field);
        $field->addMap($this);
    }

    /**
     * @param \App\User\Domain\Resource $resource
     * @return void
     */
    public function addResource(Resource $resource): void
    {
        if ($this->fields->contains($resource)) {
            return;
        }

        $this->resources->add($resource);
        $resource->addMap($this);
    }
}
