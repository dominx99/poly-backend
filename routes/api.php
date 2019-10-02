<?php

use App\User\Presentation\LoginController;
use App\User\Presentation\RegisterController;
use App\Map\Presentation\MapController;
use App\World\Presentation\WorldJoinController;
use App\User\Infrastructure\Middleware\JWTMiddleware;
use App\User\Presentation\UserController;
use App\World\Presentation\WorldController;
use App\System\System;
use App\User\Infrastructure\Middleware\UserBelongToWorld;
use Slim\Routing\RouteCollectorProxy;
use App\User\Presentation\UserResourcesController;
use App\Army\Http\Controllers\MapArmyUnitsController;
use App\Army\Http\Actions\PutMapObjectAction;
use App\User\Http\Actions\UpdateUserResourcesAction;
use App\User\Http\Controllers\PlayersController;

$app->group('/api', function (RouteCollectorProxy $group) use ($app) {
    $group->post('/auth/login', [LoginController::class, 'login']);
    $group->post('/auth/register', [RegisterController::class, 'register']);

    $group->post('/auth/login/{provider}', [LoginController::class, 'loginByProvider']);

    $group->group('', function (RouteCollectorProxy $group) use ($app) {
        $group->get('/user', [UserController::class, 'show']);
        $group->get('/user/resources', [UserResourcesController::class, 'show']);
        $group->post('/user/resources/update', UpdateUserResourcesAction::class);

        $group->get('/map/{mapId}/army-units', [MapArmyUnitsController::class, 'index']);
        $group->post('/map/put', PutMapObjectAction::class);

        $group->post('/worlds', [WorldJoinController::class, 'store']);

        $group->group('', function (RouteCollectorProxy $group) {
            $group->get('/world/players', [PlayersController::class, 'index']);

            $group->get('/world/{worldId}', [WorldController::class, 'show']);
            $group->get('/world/{worldId}/map', [MapController::class, 'show']);
        })->add(new UserBelongToWorld($app->getContainer()->get(System::class)));
    })->add(new JWTMiddleware());
});
