<?php declare(strict_types = 1);

namespace App\User\Presentation;

use App\System\System;
use App\User\Application\FindUser;
use App\User\Responses\UserSuccess;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\System\Responses\Fail;
use App\System\Infrastructure\StatusMessage;
use App\System\Infrastructure\StatusCode;
use App\System\Infrastructure\Exceptions\UserNotFoundException;

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
        try {
            $user = $this->system->execute(new FindUser($request->getAttribute('decodedToken')['id']));
        } catch (UserNotFoundException $e) {
            return (new Fail(['error' => StatusMessage::USER_NOT_FOUND], StatusCode::HTTP_NOT_FOUND))->toResponse();
        }

        return (new UserSuccess($user))->toResponse();
    }
}
