<?php declare(strict_types = 1);

namespace App\World\Presentation;

use Ramsey\Uuid\Uuid;
use App\System\Responses\Success;
use Slim\Http\Request;
use Slim\Http\Response;
use App\System\System;
use App\World\Application\CreateWorld;
use App\World\Domain\World\Status;
use App\World\Application\UserJoinWorld;
use App\World\Application\IsWorldReady;
use App\World\Application\StartWorld;
use App\World\Application\GetWorldPossibleToJoin;
use App\System\Responses\Fail;
use App\System\Infrastructure\StatusMessage;
use App\User\Application\AlreadyInGame;

class WorldJoinController
{
    /**
     * @var \App\System\System
     */
    private $system;

    /**
     * @param \App\System\System $system
     */
    public function __construct(System $system)
    {
        $this->system = $system;
    }

    /**
     * @param \Slim\Http\Request $request
     * @return \Slim\Http\Response
     */
    public function store(Request $request): Response
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

        $isReady = $this->system->execute(new IsWorldReady($id));

        if ($isReady) {
            $this->system->handle(new StartWorld($id));
        }

        return (new Success())->toResponse();
    }
}
