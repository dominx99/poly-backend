<?php declare(strict_types=1);

namespace App\Building\Http\Controllers;

use App\System\System;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Building\Application\Commands\GetBuildingUnitsByMap;
use Slim\Routing\RouteContext;

final class MapBuildingUnitsController
{
    /** @var \App\System\System */
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
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $routeContext = RouteContext::fromRequest($request);
        $route        = $routeContext->getRoute();

        $buildings = $this->system->execute(new GetBuildingUnitsByMap($route->getArgument('mapId')));

        return $buildings->toResponse();
    }
}
