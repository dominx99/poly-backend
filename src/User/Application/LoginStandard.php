<?php declare(strict_types = 1);

namespace App\User\Application;

use App\System\Contracts\Command;

class LoginStandard implements Command
{
    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $password;

    /**
     * @param string $email
     * @param string $password
     */
    public function __construct(string $email = null, string $password = null)
    {
        $this->email    = $email;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function email(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function password(): string
    {
        return $this->password;
    }
}
