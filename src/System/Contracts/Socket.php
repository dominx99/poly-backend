<?php declare(strict_types = 1);

namespace App\System\Contracts;

interface Socket
{
    public function trigger(SocketEvent $event): void;
}
