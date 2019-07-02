<?php declare (strict_types = 1);

namespace App\User\Application;

use App\System\Contracts\Command;

class FindUserByEmail implements Command
{
    /**
     * @var string
     */
    private $email;

    /**
     * @param string $email
     */
    public function __construct(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function email(): string
    {
        return $this->email;
    }
}
