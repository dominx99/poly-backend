<?php declare (strict_types = 1);

namespace Wallet\Wallet\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;
use Wallet\User\Domain\User;
use Wallet\Wallet\Domain\Wallet\Name;
use Wallet\Wallet\Domain\Wallet\Slug;
use \Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="wallets")
 */
class Wallet
{
    /**
     * @var \Ramsey\Uuid\UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private $id;

    /**
     * @var \Wallet\Wallet\Domain\Wallet\Name
     *
     * @ORM\Embedded(class="\Wallet\Wallet\Domain\Wallet\Name", columnPrefix=false)
     */
    private $name;

    /**
     * @var \Wallet\Wallet\Domain\Wallet\Slug
     *
     * @ORM\Embedded(class="\Wallet\Wallet\Domain\Wallet\Slug", columnPrefix=false)
     */
    private $slug;

    /**
     * @var \Wallet\User\Domain\User
     *
     * @ORM\ManyToOne(targetEntity="Wallet\User\Domain\User", inversedBy="wallets")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     */
    private $owner;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection|\Wallet\User\Domain\User[]
     *
     * @ORM\ManyToMany(targetEntity="Wallet\User\Domain\User", mappedBy="sharedWallets")
     */
    private $members;

    /**
     * @param \Ramsey\Uuid\Uuid                 $id
     * @param \Wallet\Wallet\Domain\Wallet\Name    $name
     * @param \Wallet\Wallet\Domain\Wallet\Slug $slug
     */
    public function __construct(Uuid $id, Name $name, Slug $slug)
    {
        $this->id      = $id;
        $this->name    = $name;
        $this->slug    = $slug;
        $this->members = new ArrayCollection();
    }

    /**
     * @param \Wallet\User\Domain\User $owner
     * @return void
     */
    public function addOwner(User $owner): void
    {
        if ($this->owner === $owner) {
            return;
        }

        $this->owner = $owner;
        $owner->addWallet($this);
    }

    /**
     * @param \Wallet\User\Domain\User $member
     * @return void
     */
    public function addMember(User $member): void
    {
        if ($this->members->contains($member)) {
            return;
        }

        $this->members->add($member);
        $member->joinWallet($this);
    }
}
