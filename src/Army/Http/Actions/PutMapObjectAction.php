<?php declare(strict_types=1);

namespace App\Army\Http\Actions;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\System\System;
use App\Map\Application\Commands\CanPutMapObject;
use App\System\Responses\SuccessResponse;
use App\Map\Application\Commands\PutMapObject;

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
        $params = array_merge($request->getParams(), ['user_id' => $request->getAttribute('decodedToken')['id']]);

        if ($this->system->execute(new CanPutMapObject($params))) {
            $this->system->handle(new PutMapObject($params));
        }

        return SuccessResponse::respond();
    }
}
