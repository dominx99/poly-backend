<?php declare (strict_types = 1);

namespace Wallet\User\Application;

use Wallet\System\Contracts\Query;

class LoginStandard implements Query
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
     * @return array
     */
    public function credentials(): array
    {
        return [
            'email'    => $this->email,
            'password' => $this->password,
        ];
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
