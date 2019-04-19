<?php

use Wallet\User\Presentation\LoginController;
use Wallet\User\Presentation\RegisterController;

$app->group('/api', function () use ($app) {
    $app->post('/auth/login', [LoginController::class, 'login']);
    $app->post('/auth/register', [RegisterController::class, 'register']);
});
