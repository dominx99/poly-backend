<?php declare(strict_types=1);

namespace App\Map\Presentation;

use Slim\Http\Response;
use Slim\Http\Request;
use App\System\System;
use App\Map\Application\FindMap;

final class MapController
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
        $route   = $request->getAttribute('route');
        $worldId = $route->getArgument('worldId');

        $map = $this->system->execute(new FindMap($worldId));

        return $map->toResponse();
    }
}
