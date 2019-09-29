<?php declare(strict_types=1);

namespace App\User\Application\Commands;

use App\System\Contracts\Command;

final class AssignUserColors implements Command
{
    /** @var array */
    private $userIds;

    /**
     * @param array $userIds
     */
    public function __construct(array $userIds)
    {
        $this->userIds = $userIds;
    }

    /**
     * @return array
     */
    public function userIds(): array
    {
        return $this->userIds;
    }
}
