<?php declare(strict_types = 1);

namespace App\World\Presentation;

use App\System\Infrastructure\StatusMessage;
use App\System\System;
use App\World\Application\FindWorld;
use App\System\Responses\Fail;
use App\World\Responses\WorldSuccess;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\System\Infrastructure\StatusCode;

class WorldController
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
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function show(ServerRequestInterface $request): ResponseInterface
    {
        $route = $request->getAttribute('route');

        $world = $this->system->execute(new FindWorld($route->getArgument('worldId')));

        if (! $world) {
            return (new Fail(['error' => StatusMessage::WORLD_NOT_FOUND], StatusCode::HTTP_NOT_FOUND))->toResponse();
        }

        return (new WorldSuccess($world))->toResponse();
    }
}
