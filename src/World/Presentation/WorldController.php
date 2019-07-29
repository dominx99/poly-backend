<?php declare(strict_types = 1);

namespace App\World\Presentation;

use App\System\Infrastructure\StatusMessage;
use Slim\Http\Request;
use Slim\Http\Response;
use App\System\System;
use App\World\Application\FindWorld;
use App\System\Responses\Fail;
use App\World\Responses\WorldSuccess;

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
     * @param \Slim\Http\Request $request
     * @return \Slim\Http\Response
     */
    public function show(Request $request): Response
    {
        $route = $request->getAttribute('route');

        $world = $this->system->execute(new FindWorld($route->getArgument('worldId')));

        if (! $world) {
            return (new Fail(['error' => StatusMessage::WORLD_NOT_FOUND], 404))->toResponse();
        }

        return (new WorldSuccess($world))->toResponse();
    }
}
