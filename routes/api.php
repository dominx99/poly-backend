<?php

use App\User\Presentation\LoginController;
use App\User\Presentation\RegisterController;
use App\Map\Presentation\MapController;

$app->group('/api', function () use ($app) {
    $app->post('/auth/login', [LoginController::class, 'login']);
    $app->post('/auth/register', [RegisterController::class, 'register']);

    $app->post('/auth/login/{provider}', [LoginController::class, 'loginByProvider']);

    $app->get('/map/generate', [MapController::class, 'generate']);
});
