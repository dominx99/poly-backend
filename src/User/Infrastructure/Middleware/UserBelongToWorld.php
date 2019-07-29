<?php declare(strict_types = 1);

namespace App\User\Infrastructure\Middleware;

use App\User\Contracts\UserQueryRepository;
use App\System\System;
use Slim\Http\Request;
use Slim\Http\Response;
use App\System\Infrastructure\StatusMessage;
use App\User\Application\FindUser;
use App\System\Responses\Fail;
use Psr\Http\Message\ResponseInterface;

class UserBelongToWorld
{
    /**
     * @var \App\System\System
     */
    private $system;

    /**
     * @var \App\User\Contracts\UserQueryRepository
     */
    private $users;

    /**
     * @param \App\System\System $system
     * @param \App\User\Contracts\UserQueryRepository $users
     * @return void
     */
    public function __construct(System $system, UserQueryRepository $users)
    {
        $this->system = $system;
        $this->users  = $users;
    }

    /**
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @param mixed $next
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(Request $request, Response $response, $next): ResponseInterface
    {
        $userId = $request->getAttribute('decodedToken')['id'];
        $user   = $this->system->execute(new FindUser($userId));

        $route   = $request->getAttribute('route');
        $worldId = $route->getArgument('worldId');

        if ($user->worldId() === null || $user->worldId() !== $worldId) {
            return (new Fail(['error' => StatusMessage::USER_NOT_BELONG_TO_WORLD], 422))->toResponse();
        }

        return $next($request, $response);
    }
}
