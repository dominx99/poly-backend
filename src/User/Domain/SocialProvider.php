<?php declare(strict_types = 1);

namespace App\User\Domain;

use Ramsey\Uuid\Uuid;
use App\User\Domain\SocialProvider\Name;
use \Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="social_providers")
 */
class SocialProvider
{
    /**
     * @var \Ramsey\Uuid\UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private $id;

    /**
     * @var \App\User\Domain\User
     *
     * @ORM\ManyToOne(targetEntity="App\User\Domain\User", inversedBy="providers")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var \App\User\Domain\SocialProvider\Name
     *
     * @ORM\Embedded(class="\App\User\Domain\SocialProvider\Name", columnPrefix=false)
     */
    private $name;

    /**
     * @param \Ramsey\Uuid\UuidInterface $id
     * @param \App\User\Domain\SocialProvider\Name $name
     */
    public function __construct(UuidInterface $id, Name $name)
    {
        $this->id   = $id;
        $this->name = $name;
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
        $user->addSocialProvider($this);
    }
}
