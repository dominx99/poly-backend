<?php declare(strict_types = 1);

namespace App\User\Infrastructure\Middleware;

use App\System\System;
use App\System\Infrastructure\StatusMessage;
use App\User\Application\FindUser;
use App\System\Responses\Fail;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UserBelongToWorld implements MiddlewareInterface
{
    /**
     * @var \App\System\System
     */
    private $system;

    /**
     * @param \App\System\System $system
     * @param \App\User\Contracts\UserQueryRepository $users
     */
    public function __construct(System $system)
    {
        $this->system = $system;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $userId = $request->getAttribute('decodedToken')['id'];
        $user   = $this->system->execute(new FindUser($userId));

        $route   = $request->getAttribute('route');
        if (! $worldId = $route->getArgument('worldId')) {
            $worldId = $request->getParam('world_id');
        }

        if ($user->worldId() === null || $user->worldId() !== $worldId) {
            return (new Fail(['error' => StatusMessage::USER_NOT_BELONG_TO_WORLD], 422))->toResponse();
        }

        return $handler->handle($request);
    }
}
