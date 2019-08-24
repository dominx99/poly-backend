<?php declare(strict_types = 1);

namespace App\System\Infrastructure;

use App\System\Contracts\Socket;
use Pusher\Pusher;
use App\System\Contracts\SocketEvent;

class PusherSocket implements Socket
{
    /**
     * @var \Pusher\Pusher
     */
    private $pusher;

    /**
     * @param \Pusher\Pusher $pusher
     */
    public function __construct(Pusher $pusher)
    {
        $this->pusher = $pusher;
    }

    /**
     * @param \App\System\Contracts\SocketEvent $event
     * @return void
     */
    public function trigger(SocketEvent $event): void
    {
        $this->pusher->trigger($event->channel(), $event->name(), $event->data());
    }
}
