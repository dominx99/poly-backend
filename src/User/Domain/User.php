<?php declare (strict_types = 1);

namespace App\User\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;
use App\User\Domain\User\Email;
use App\User\Domain\User\Password;
use \Doctrine\ORM\Mapping as ORM;

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
     * @param \Ramsey\Uuid\Uuid                 $id
     * @param \App\User\Domain\User\Email    $email
     * @param \App\User\Domain\User\Password|null $password
     */
    public function __construct(Uuid $id, Email $email, Password $password = null)
    {
        $this->id            = $id;
        $this->email         = $email;
        $this->password      = $password;
        $this->providers     = new ArrayCollection();
    }

    /**
     * @param SocialProvider $provider
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
}

