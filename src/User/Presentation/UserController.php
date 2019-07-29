<?php declare(strict_types = 1);

namespace App\User\Presentation;

use App\System\System;
use Slim\Http\Request;
use App\User\Application\FindUser;
use App\User\Responses\UserSuccess;
use Slim\Http\Response;

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
     * @param \Slim\Http\Request $request
     * @return \Slim\Http\Response
     */
    public function show(Request $request): Response
    {
        $user = $this->system->execute(new FindUser($request->getAttribute('decodedToken')['id']));

        return (new UserSuccess($user))->toResponse();
    }
}
