<?php declare(strict_types = 1);

namespace App\World\Application;

use App\System\Contracts\Command;

class UserJoinWorld implements Command
{
    /**
     * @var string
     */
    private $userId;

    /**
     * @var string
     */
    private $worldId;

    /**
     * @param string $userId
     * @param string $worldId
     */
    public function __construct(string $userId, string $worldId)
    {
        $this->userId  = $userId;
        $this->worldId = $worldId;
    }

    /**
     * @return string
     */
    public function userId(): string
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function worldId(): string
    {
        return $this->worldId;
    }
}
