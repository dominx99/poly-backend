<?php declare(strict_types = 1);

namespace App\User\Presentation;

use Slim\Http\Request;
use Slim\Http\Response;
use App\System\Responses\ValidationFail;
use App\System\System;
use App\User\Application\LoginStandard;
use App\User\Application\RegisterStandard;
use App\User\Application\Validation\UserStoreValidator;

class RegisterController
{
    /**
     * @var \App\System\System
     */
    protected $system;

    /**
     * @var \App\User\Application\Validation\UserStoreValidator
     */
    protected $validator;

    /**
     * @param \App\System\System $system
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
        $validation = $this->validator->validate($request->getParams() ?? []);

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
