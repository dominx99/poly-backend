<?php declare (strict_types = 1);

namespace Wallet\User\Presentation;

use Slim\Http\Request;
use Slim\Http\Response;
use Wallet\System\Responses\ValidationFail;
use Wallet\System\System;
use Wallet\User\Application\LoginStandard;
use Wallet\User\Application\RegisterStandard;
use Wallet\User\Application\UserStoreValidator;

class RegisterController
{
    /**
     * @var \Wallet\System\System
     */
    protected $system;

    /**
     * @var \Wallet\User\Application\UserStoreValidator
     */
    protected $validator;

    /**
     * @param \Wallet\System\System $system
     */
    public function __construct(System $system, UserStoreValidator $validator)
    {
        $this->system    = $system;
        $this->validator = $validator;
    }

    /**
     * @param  \Slim\Http\Request $request
     * @return \Slim\Http\Response
     */
    public function register(Request $request): Response
    {
        $validation = $this->validator->validate($request->getParams());

        if ($validation->failed()) {
            return (new ValidationFail($validation->getErrors()))->toResponse();
        }

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
