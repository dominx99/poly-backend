<?php declare (strict_types = 1);

namespace Wallet\User\Presentation;

use Slim\Http\Request;
use Slim\Http\Response;
use Wallet\System\System;
use Wallet\User\Application\LoginStandard;
use Wallet\User\Application\RegisterStandard;

class RegisterController
{
    /**
     * @var \Wallet\System\System
     */
    protected $system;

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
    public function register(Request $request): Response
    {
        // !TODO - validation here

        $registerCommand = new RegisterStandard(
            $request->getParam('email'),
            $request->getParam('password')
        );

        $this->system->handle($registerCommand);

        $loginCommand = new LoginStandard(
            $request->getParam('email'),
            $request->getParam('password')
        );

        $result = $this->system->execute($loginCommand);

        return $result->toResponse();
    }
}
