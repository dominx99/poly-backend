<?php declare(strict_types = 1);

namespace App\World\Application;

use App\System\Contracts\Command;

class IsWorldReady implements Command
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var int
     */
    private $trigger;

    /**
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id      = $id;
        $this->trigger = 3;
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function trigger(): int
    {
        return $this->trigger;
    }
}
