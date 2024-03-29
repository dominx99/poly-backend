<?php declare(strict_types = 1);

namespace App\User\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use App\User\Domain\User\Email;
use App\User\Domain\User\Password;
use \Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use App\World\Domain\World;
use App\Map\Domain\Map\MapObject;
use App\Map\Domain\Map\Field;
use App\System\Contracts\Buyable;
use App\User\Domain\User\Color;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User
{
    /**
     * @var \Ramsey\Uuid\UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private $id;

    /**
     * @var \App\User\Domain\User\Email
     *
     * @ORM\Embedded(class="\App\User\Domain\User\Email", columnPrefix=false)
     */
    private $email;

    /**
     * @var \App\User\Domain\User\Password
     *
     * @ORM\Embedded(class="\App\User\Domain\User\Password", columnPrefix=false)
     */
    private $password;

    /**
     * @var \App\User\Domain\User\Color
     *
     * @ORM\Embedded(class="\App\User\Domain\User\Color", columnPrefix=false)
     */
    private $color;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection|\App\User\Domain\SocialProvider[]
     *
     * @ORM\OneToMany(targetEntity="App\User\Domain\SocialProvider", mappedBy="user", cascade={"persist"})
     */
    private $providers;

    /**
     * @var \App\World\Domain\World
     *
     * @ORM\ManyToOne(targetEntity="\App\World\Domain\World", inversedBy="users")
     * @ORM\JoinColumn(name="world_id", referencedColumnName="id")
     */
    private $world;

    /**
     * @var \App\User\Domain\Resource|null
     *
     * @ORM\OneToOne(targetEntity="\App\User\Domain\Resource", mappedBy="user")
     */
    private $resource;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection|\App\Map\Domain\Map\Field[]
     *
     * @ORM\OneToMany(targetEntity="\App\Map\Domain\Map\Field", mappedBy="user", cascade={"persist"})
     */
    private $fields;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection|\App\Map\Domain\Map\MapObject[]
     *
     * @ORM\OneToMany(targetEntity="\App\Map\Domain\Map\MapObject", mappedBy="user", cascade={"persist"})
     */
    private $mapObjects;

    /**
     * @param \Ramsey\Uuid\UuidInterface $id
     * @param \App\User\Domain\User\Email $email
     * @param \App\User\Domain\User\Password $password
     */
    public function __construct(UuidInterface $id, Email $email, Password $password = null)
    {
        $this->id         = $id;
        $this->email      = $email;
        $this->password   = $password;
        $this->providers  = new ArrayCollection();
        $this->resource   = null;
        $this->fields     = new ArrayCollection();
        $this->mapObjects = new ArrayCollection();
    }

    /**
     * @param \App\User\Domain\SocialProvider $provider
     * @return void
     */
    public function addSocialProvider(SocialProvider $provider): void
    {
        if ($this->providers->contains($provider)) {
            return;
        }

        $this->providers->add($provider);
        $provider->addUser($this);
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
        $world->addUser($this);
    }

    /**
     * @param \App\User\Domain\Resource $resource
     * @return void
     */
    public function setResource(Resource $resource): void
    {
        if ($this->resource === $resource) {
            return;
        }

        $this->resource = $resource;
        $resource->addUser($this);
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
        $mapObject->setUser($this);
    }

    /**
     * @param \App\Map\Domain\Map\Field
     * @return void
     */
    public function addField(Field $field): void
    {
        if ($this->fields->contains($field)) {
            return;
        }

        $this->fields->add($field);
        $field->setUser($this);
    }

    /**
     * @param \App\System\Contracts\Buyable $unit
     * @return void
     */
    public function buy(Buyable $unit): void
    {
        $this->resource->buy($unit->getCost());
    }

    /**
     * @param \App\Map\Domain\Map\Field $field
     * @return void
     */
    public function gainField(Field $field): void
    {
        $this->addField($field);
    }

    /**
     * @param string $color
     * @return void
     */
    public function setColor(string $color): void
    {
        $this->color = new Color($color);
    }
}
