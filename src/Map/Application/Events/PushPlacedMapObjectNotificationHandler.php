<?php declare(strict_types=1);

namespace App\Map\Application\Events;

use App\Map\Application\Notifications\PlacedMapObjectNotification;
use App\System\Contracts\Socket;
use App\System\System;
use App\Map\Application\Commands\FindMapObject;

final class PushPlacedMapObjectNotificationHandler
{
    /** @var \App\System\System */
    private $system;

    /** @var \App\System\Contracts\Socket */
    private $socket;

    /**
     * @param \App\System\System $system
     * @param \App\System\Contracts\Socket $socket
     */
    public function __construct(System $system, Socket $socket)
    {
        $this->system = $system;
        $this->socket = $socket;
    }

    /**
     * @param \App\Map\Application\Events\PlacedMapObject $event
     * @return void
     */
    public function handle(PlacedMapObject $event): void
    {
        $this->socket->trigger(new PlacedMapObjectNotification(
            $this->system->execute(new FindMapObject($event->mapObjectId()))
        ));
    }
}
