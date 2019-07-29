<?php declare(strict_types = 1);

namespace App\World\Application;

use App\System\Contracts\Command;

class UpdateWorldStatus implements Command
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $status;

    /**
     * @param string $id
     * @param string $status
     */
    public function __construct(string $id, string $status)
    {
        $this->id     = $id;
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function status(): string
    {
        return $this->status;
    }
}
