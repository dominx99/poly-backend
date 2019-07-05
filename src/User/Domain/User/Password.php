<?php declare(strict_types = 1);

namespace App\User\Domain\User;

use \Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class Password
{
    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", nullable=true)
     */
    private $password;

    /**
     * @param string $password
     */
    public function __construct(string $password)
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }
}
