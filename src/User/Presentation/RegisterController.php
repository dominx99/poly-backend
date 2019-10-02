<?php declare(strict_types = 1);

namespace App\User\Presentation;

use App\System\Responses\ValidationFail;
use App\System\System;
use App\User\Application\LoginStandard;
use App\User\Application\RegisterStandard;
use App\User\Application\Validation\UserStoreValidator;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

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
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function register(ServerRequestInterface $request): ResponseInterface
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
