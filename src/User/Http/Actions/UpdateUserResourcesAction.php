<?php declare(strict_types=1);

namespace App\User\Http\Actions;

use App\System\Responses\SuccessResponse;
use Psr\Http\Message\ResponseInterface;
use App\User\Application\Commands\UpdateUserResources;
use App\System\System;
use Psr\Http\Message\ServerRequestInterface;

final class UpdateUserResourcesAction
{
    /** @var \App\System\System */
    private $system;

    /**
     * @param \App\System\System $system
     */
    public function __construct(System $system)
    {
        $this->system = $system;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $this->system->handle(
            new UpdateUserResources(
                $request->getAttribute('decodedToken')['id'],
                $request->getParam('map_id')
            ),
            [TransactionDecorator::class]
        );

        return SuccessResponse::respond();
    }
}
