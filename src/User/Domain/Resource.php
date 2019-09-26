<?php declare(strict_types = 1);

namespace App\User\Domain;

use \Doctrine\ORM\Mapping as ORM;
use App\User\Domain\Resource\Gold;
use App\Map\Domain\Map;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity
 * @ORM\Table(name="resources")
 */
class Resource
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private $id;

    /**
     * @var \App\User\Domain\User
     *
     * @ORM\OneToOne(targetEntity="App\User\Domain\User", inversedBy="resource")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var \App\Map\Domain\Map
     *
     * @ORM\ManyToOne(targetEntity="\App\Map\Domain\Map", inversedBy="resources")
     * @ORM\JoinColumn(name="map_id", referencedColumnName="id")
     */
    private $map;

    /**
     * @var \App\User\Domain\Resource\Gold
     *
     * @ORM\Embedded(class="\App\User\Domain\Resource\Gold", columnPrefix=false)
     */
    private $gold;

    /**
     * @param string $id
     * @param \App\User\Domain\Resource\Gold $gold
     */
    public function __construct(string $id, Gold $gold)
    {
        $this->id   = $id;
        $this->gold = $gold;
    }

    /**
     * @return self
     */
    public static function createDefault(): self
    {
        return new self((string) Uuid::uuid4(), Gold::createDefault());
    }

    /**
     * @param \App\User\Domain\User $user
     * @return void
     */
    public function addUser(User $user): void
    {
        if ($this->user === $user) {
            return;
        }

        $this->user = $user;
        $user->setResource($this);
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
        $map->addResource($this);
    }

    /**
     * @param int $cost
     * @return void
     */
    public function reduceGold(int $cost): void
    {
        $this->gold->reduce($cost);
    }
}
