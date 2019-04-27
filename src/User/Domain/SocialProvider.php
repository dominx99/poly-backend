<?php declare (strict_types = 1);

namespace Wallet\User\Domain;

use Ramsey\Uuid\Uuid;
use Wallet\User\Domain\SocialProvider\Name;
use \Doctrine\ORM\Mapping as ORM;

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
     * @var \Wallet\User\Domain\User
     *
     * @ORM\ManyToOne(targetEntity="Wallet\User\Domain\User", inversedBy="providers")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var \Wallet\User\Domain\SocialProvider\Name
     *
     * @ORM\Embedded(class="\Wallet\User\Domain\SocialProvider\Name", columnPrefix=false)
     */
    private $name;

    /**
     * @param \Ramsey\Uuid\Uuid $id
     * @param \Wallet\User\Domain\User $user
     * @param string $name
     */
    public function __construct(Uuid $id, User $user, Name $name)
    {
        $this->id   = $id;
        $this->user = $user;
        $this->name = $name;
    }
}
