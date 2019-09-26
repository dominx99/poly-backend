<?php declare(strict_types=1);

namespace App\Army\Http\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\RequestInterface;
use Slim\Routing\RouteContext;
use App\System\System;
use App\Army\Application\Commands\GetArmyUnits;

final class MapArmyUnitsController
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
     * @param \Psr\Http\Message\RequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index(RequestInterface $request): ResponseInterface
    {
        $routeContext = RouteContext::fromRequest($request);
        $route        = $routeContext->getRoute();

        $mapId = $route->getArgument('mapId');

        $armies = $this->system->execute(new GetArmyUnits($mapId));

        return $armies->toResponse();
    }
}
