<?php declare(strict_types = 1);

namespace App\User\Application;

use App\System\Contracts\Command;

class AlreadyInGame implements Command
{
    /**
     * @var string
     */
    private $userId;

    /**
     * @param string $userId
     */
    public function __construct(string $userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function userId(): string
    {
        return $this->userId;
    }
}
