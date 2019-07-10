<?php declare(strict_types = 1);

namespace App\World\Domain;

use \Doctrine\ORM\Mapping as ORM;
use App\World\Domain\World\Status;
use Doctrine\Common\Collections\ArrayCollection;
use App\User\Domain\User;
use App\Map\Domain\Map;

/**
 * @ORM\Entity
 * @ORM\Table(name="worlds")
 */
class World
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private $id;

    /**
     * @var \App\World\Domain\World\Status
     *
     * @ORM\Embedded(class="\App\World\Domain\World\Status", columnPrefix=false)
     */
    private $status;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection|\App\User\Domain\User[]
     *
     * @ORM\OneToMany(targetEntity="\App\User\Domain\User", mappedBy="world", cascade={"persist"})
     */
    private $users;

    /**
     * @var \App\Map\Domain\Map
     *
     * @ORM\OneToOne(targetEntity="\App\Map\Domain\Map", mappedBy="world")
     */
    private $map;

    public function __construct(string $id, Status $status)
    {
        $this->id     = $id;
        $this->status = $status;
        $this->users  = new ArrayCollection();
    }

    /**
     * @param \App\User\Domain\User $user
     * @return void
     */
    public function addUser(User $user): void
    {
        if ($this->users->contains($user)) {
            return;
        }

        $this->users->add($user);
        $user->addWorld($this);
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
        $map->addWorld($this);
    }
}
