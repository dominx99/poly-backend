<?php declare(strict_types=1);

namespace App\Army\Http\Actions;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\System\System;
use App\Map\Application\Commands\CanPutMapObject;
use App\System\Responses\SuccessResponse;
use App\Map\Application\Commands\PutMapObject;
use App\System\Infrastructure\Exceptions\UnexpectedException;
use App\User\Application\Commands\CanUserAffordUnit;
use Psr\Log\LoggerInterface;
use App\User\Application\Commands\ReduceUserGoldForUnit;
use App\User\Application\Commands\UserGainField;
use App\Map\Application\Commands\RemoveCurrentMapObject;

final class PutMapObjectAction
{
    /**
     * @var \App\System\System
     */
    private $system;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $log;

    /**
     * @param \App\System\System $system
     * @param \Psr\Log\LoggerInterface $log
     */
    public function __construct(System $system, LoggerInterface $log)
    {
        $this->system = $system;
        $this->log    = $log;
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \App\System\Infrastructure\Exceptions\UnexpectedException
     */
    public function __invoke(RequestInterface $request): ResponseInterface
    {
        // TODO: Add validation

        $userId = $request->getAttribute('decodedToken')['id'];
        $params = array_merge($request->getParams(), ['user_id' => $userId]);

        if ($this->isPossibleToPutMapObject($params)) {
            try {
                $this->system->handle(new ReduceUserGoldForUnit($userId, $request->getParam('unit_id')));
                $this->system->handle(new UserGainField($userId, $request->getParam('field_id')));
                $this->system->handle(new RemoveCurrentMapObject($params['field_id']));
                $this->system->handle(new PutMapObject($params));
            } catch (\Throwable $t) {
                $this->log->error($t->getMessage());

                throw new UnexpectedException();
            }
        }

        return SuccessResponse::respond();
    }

    /**
     * @param array $params
     * @return bool
     */
    private function isPossibleToPutMapObject(array $params): bool
    {
        return
            $this->system->execute(new CanPutMapObject($params)) &&
            $this->system->execute(new CanUserAffordUnit($params['user_id'], $params['unit_id']));
    }
}
