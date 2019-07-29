<?php

use App\User\Presentation\LoginController;
use App\User\Presentation\RegisterController;
use App\Map\Presentation\MapController;
use App\World\Presentation\WorldJoinController;
use App\User\Infrastructure\Middleware\JWTMiddleware;
use App\User\Presentation\UserController;
use App\World\Presentation\WorldController;
use App\System\System;
use App\User\Contracts\UserQueryRepository;
use App\User\Infrastructure\Middleware\UserBelongToWorld;

$app->group('/api', function () use ($app) {
    $app->post('/auth/login', [LoginController::class, 'login']);
    $app->post('/auth/register', [RegisterController::class, 'register']);

    $app->post('/auth/login/{provider}', [LoginController::class, 'loginByProvider']);

    $app->get('/map/generate', [MapController::class, 'generate']);

    $app->group('', function () use ($app) {
        $app->get('/user', [UserController::class, 'show']);

        $app->post('/worlds', [WorldJoinController::class, 'store']);

        $app->group('', function () use ($app) {
            $app->get('/world/{worldId}', [WorldController::class, 'show']);
            $app->get('/map/{worldId}', [MapController::class, 'show']);
        })->add(new UserBelongToWorld($app->getContainer()->get(System::class), $app->getContainer()->get(UserQueryRepository::class)));
    })->add(new JWTMiddleware());
});
