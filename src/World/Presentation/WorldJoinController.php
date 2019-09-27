<?php declare(strict_types = 1);

namespace App\World\Presentation;

use Ramsey\Uuid\Uuid;
use App\System\Responses\Success;
use App\System\System;
use App\World\Application\CreateWorld;
use App\World\Domain\World\Status;
use App\World\Application\UserJoinWorld;
use App\World\Application\IsWorldReady;
use App\World\Application\GetWorldPossibleToJoin;
use App\System\Responses\Fail;
use App\System\Infrastructure\StatusMessage;
use App\User\Application\AlreadyInGame;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\RequestInterface;
use App\System\Infrastructure\Event\EventDispatcherInterface;
use App\World\Application\Events\WorldReady;

class WorldJoinController
{
    /**
     * @var \App\System\System
     */
    private $system;

    /** @var \App\System\Infrastructure\Event\EventDispatcherInterface */
    private $events;

    /**
     * @param \App\System\System $system
     * @param \App\System\Infrastructure\Event\EventDispatcherInterface $events
     */
    public function __construct(System $system, EventDispatcherInterface $events)
    {
        $this->system = $system;
        $this->events = $events;
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function store(RequestInterface $request): ResponseInterface
    {
        if ($this->system->execute(
            new AlreadyInGame($request->getAttribute('decodedToken')['id'])
        )) {
            return (new Fail(['error' => StatusMessage::ALREADY_IN_GAME]))->toResponse();
        }

        $world = $this->system->execute(new GetWorldPossibleToJoin());

        if (! $world) {
            $id = (string) Uuid::uuid4();
            $this->system->handle(new CreateWorld($id, Status::CREATED));
        } else {
            $id = $world->id();
        }

        $this->system->handle(new UserJoinWorld($request->getAttribute('decodedToken')['id'], $id));

        if ($this->system->execute(new IsWorldReady($id))) {
            $this->events->dispatch(new WorldReady($id));
        }

        return (new Success(['world_id' => $id]))->toResponse();
    }
}
