<?php declare(strict_types = 1);

namespace App\World\Events;

use App\System\Contracts\Event;

class ChangeWorldStatusEvent implements Event
{
    /**
     * @var string
     */
    private $worldId;

    /**
     * @var string
     */
    private $status;

    /**
     * @param string $worldId
     * @param string $status
     */
    public function __construct(string $worldId, string $status)
    {
        $this->worldId = $worldId;
        $this->status  = $status;
    }

    /**
     * @return string
     */
    public function channel(): string
    {
        return $this->worldId;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return 'update.status';
    }

    /**
     * @return array
     */
    public function data(): array
    {
        return ['status' => $this->status];
    }
}
