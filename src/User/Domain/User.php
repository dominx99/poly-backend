<?php declare (strict_types = 1);

namespace Wallet\User\Domain;

use Ramsey\Uuid\Uuid;
use Wallet\User\Domain\User\Email;
use Wallet\User\Domain\User\Password;
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
     * @param \Ramsey\Uuid\Uuid                 $id
     * @param \Wallet\User\Domain\User\Email    $email
     * @param \Wallet\User\Domain\User\Password $password
     */
    public function __construct(Uuid $id, Email $email, Password $password)
    {
        $this->id       = $id;
        $this->email    = $email;
        $this->password = $password;
    }
}
