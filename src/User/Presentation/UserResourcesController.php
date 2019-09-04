<?php declare(strict_types = 1);

namespace App\User\Presentation;

use App\System\System;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\User\Application\GetUserResources;

class UserResourcesController
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

    public function show(RequestInterface $request): ResponseInterface
    {
        $result = $this->system->execute(
            new GetUserResources($request->getAttribute('decodedToken')['id'])
        );

        return $result->toResponse();
    }
}
