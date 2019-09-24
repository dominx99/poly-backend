<?php declare(strict_types = 1);

namespace App\User\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use App\User\Domain\User\Email;
use App\User\Domain\User\Password;
use \Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use App\World\Domain\World;
use App\Map\Domain\Map\MapObject;

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
    public function addResource(Resource $resource): void
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
}
