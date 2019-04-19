<?php declare (strict_types = 1);

namespace Wallet\User\Presentation;

use Slim\Http\Request;
use Slim\Http\Response;
use Wallet\System\System;
use Wallet\User\Application\LoginStandard;

class LoginController
{
    /**
     * @param \Wallet\System\System $system
     */
    public function __construct(System $system)
    {
        $this->system = $system;
    }

    /**
     * @param  \Slim\Http\Request $request
     * @return \Slim\Http\Response
     */
    public function login(Request $request): Response
    {
        $params = $request->getParams();

        $query = new LoginStandard($params['email'], $params['password']);

        $result = $this->system->execute($query);

        return $result->toResponse();
    }
}
