<?php declare(strict_types=1);

namespace App\User\Application\Commands;

use App\System\Contracts\Command;

final class UpdateUserResources implements Command
{
    /** @var string */
    private $userId;

    /** @var string */
    private $mapId;

    /**
     * @param string $userId
     * @param string $mapId
     */
    public function __construct(string $userId, string $mapId)
    {
        $this->userId = $userId;
        $this->mapId  = $mapId;
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
    public function mapId(): string
    {
        return $this->mapId;
    }
}
