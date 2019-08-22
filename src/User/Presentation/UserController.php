<?php declare(strict_types = 1);

namespace App\User\Presentation;

use App\System\System;
use App\User\Application\FindUser;
use App\User\Responses\UserSuccess;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class UserController
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
    public function show(RequestInterface $request): ResponseInterface
    {
        $user = $this->system->execute(new FindUser($request->getAttribute('decodedToken')['id']));

        return (new UserSuccess($user))->toResponse();
    }
}
