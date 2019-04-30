<?php declare (strict_types = 1);

namespace Wallet\User\Application;

use Wallet\System\Contracts\Command;

class GetUserFromToken implements Command
{
    /**
     * @var string
     */
    private $token;

    /**
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function token(): string
    {
        return $this->token;
    }
}
