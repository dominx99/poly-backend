<?php declare(strict_types=1);

namespace App\User\Application\Commands;

use App\System\Contracts\Command;

final class CanUserAffordUnit implements Command
{
    /**
     * @var string
     */
    private $userId;

    /**
     * @var string
     */
    private $unitId;

    /**
     * @param string $userId
     * @param string $unitId
     */
    public function __construct(string $userId, string $unitId)
    {
        $this->userId = $userId;
        $this->unitId = $unitId;
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
    public function unitId(): string
    {
        return $this->unitId;
    }
}
