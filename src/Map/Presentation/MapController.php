<?php declare(strict_types=1);

namespace App\Map\Presentation;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
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
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function show(Request $request): Response
    {
        $route   = $request->getAttribute('route');
        $worldId = $route->getArgument('worldId');

        $map = $this->system->execute(new FindMap($worldId));

        return $map->toResponse();
    }
}
