<?php

use App\User\Presentation\LoginController;
use App\User\Presentation\RegisterController;
use App\Map\Presentation\MapController;
use App\World\Presentation\WorldJoinController;
use App\User\Infrastructure\Middleware\JWTMiddleware;

$app->group('/api', function () use ($app) {
    $app->post('/auth/login', [LoginController::class, 'login']);
    $app->post('/auth/register', [RegisterController::class, 'register']);

    $app->post('/auth/login/{provider}', [LoginController::class, 'loginByProvider']);

    $app->get('/map/generate', [MapController::class, 'generate']);

    $app->group('', function () use ($app) {
        $app->post('/worlds', [WorldJoinController::class, 'store']);
    })->add(new JWTMiddleware());
});
