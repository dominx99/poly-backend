<?php declare(strict_types = 1);

namespace App\Map\Application;

use App\System\Contracts\Command;

class FindMap implements Command
{
    /**
     * @var string
     */
    private $worldId;

    /**
     * @param string $worldId
     */
    public function __construct(string $worldId)
    {
        $this->worldId = $worldId;
    }

    /**
     * @return string
     */
    public function worldId(): string
    {
        return $this->worldId;
    }
}
