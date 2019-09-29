<?php declare(strict_types=1);

namespace App\User\Http\Controllers;

use App\System\System;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\User\Application\Commands\GetPlayers;

final class PlayersController
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
     * @param \Psr\Http\Message\RequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index(RequestInterface $request): ResponseInterface
    {
        $players = $this->system->execute(new GetPlayers($request->getParam('world_id')));

        return $players->toResponse();
    }
}
