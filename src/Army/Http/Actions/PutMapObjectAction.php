<?php declare(strict_types=1);

namespace App\Army\Http\Actions;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\System\System;
use App\Army\Application\Commands\CanPutMapObject;
use App\System\Responses\SuccessResponse;

final class PutMapObjectAction
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
    public function __invoke(RequestInterface $request): ResponseInterface
    {
        if ($this->system->execute(new CanPutMapObject(
            array_merge($request->getParams(), ['user_id' => $request->getAttribute('decodedToken')['id']])
        ))) {
            $this->system->handle(new PutMapObject($request->getParams()));
        }

        return SuccessResponse::respond();
    }
}
