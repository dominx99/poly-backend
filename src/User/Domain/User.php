<?php declare (strict_types = 1);

namespace Wallet\User\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;
use Wallet\User\Domain\User\Email;
use Wallet\User\Domain\User\Password;
use Wallet\Wallet\Domain\Wallet;
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
     * @var \Wallet\User\Domain\User\Email
     *
     * @ORM\Embedded(class="\Wallet\User\Domain\User\Email", columnPrefix=false)
     */
    private $email;

    /**
     * @var \Wallet\User\Domain\User\Password
     *
     * @ORM\Embedded(class="\Wallet\User\Domain\User\Password", columnPrefix=false)
     */
    private $password;

    /**
     * @var \Wallet\User\Domain\SocialProvider[]
     *
     * @ORM\OneToMany(targetEntity="Wallet\User\Domain\SocialProvider", mappedBy="user", cascade={"persist"})
     */
    private $providers;

    /**
     * @var \Wallet\Wallet\Domain\Wallet[]
     *
     * @ORM\OneToMany(targetEntity="Wallet\Wallet\Domain\Wallet", mappedBy="owner")
     */
    private $ownWallets;

    /**
     * @var \Wallet\Wallet\Domain\Wallet[]
     *
     * @ORM\ManyToMany(targetEntity="Wallet\Wallet\Domain\Wallet", inversedBy="members")
     * @ORM\JoinTable(
     *  name="user_wallet",
     *  joinColumns={
     *      @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *  },
     *  inverseJoinColumns={
     *      @ORM\JoinColumn(name="wallet_id", referencedColumnName="id")
     *  }
     * )
     */
    private $sharedWallets;

    /**
     * @param \Ramsey\Uuid\Uuid                 $id
     * @param \Wallet\User\Domain\User\Email    $email
     * @param \Wallet\User\Domain\User\Password|null $password
     */
    public function __construct(Uuid $id, Email $email, Password $password = null)
    {
        $this->id            = $id;
        $this->email         = $email;
        $this->password      = $password;
        $this->providers     = new ArrayCollection();
        $this->ownWallets    = new ArrayCollection();
        $this->sharedWallets = new ArrayCollection();
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

    /**
     * @param \Wallet\Wallet\Domain\Wallet $wallet
     * @return void
     */
    public function addWallet(Wallet $wallet): void
    {
        if ($this->ownWallets->contains($wallet)) {
            return;
        }

        $this->ownWallets->add($wallet);
        $wallet->addOwner($this);
    }

    /**
     * @param \Wallet\Wallet\Domain\Wallet $wallet
     * @return void
     */
    public function joinWallet(Wallet $wallet): void
    {
        if ($this->sharedWallets->contains($wallet)) {
            return;
        }

        $this->sharedWallets->add($wallet);
        $wallet->addMember($this);
    }
}
