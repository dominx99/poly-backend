<?php

use Wallet\User\Infrastructure\Middleware\JWTMiddleware;
use Wallet\User\Presentation\LoginController;
use Wallet\User\Presentation\RegisterController;
use Wallet\Wallet\Presentation\WalletController;

$app->group('/api', function () use ($app) {
    $app->post('/auth/login', [LoginController::class, 'login']);
    $app->post('/auth/register', [RegisterController::class, 'register']);

    $app->post('/auth/login/{provider}', [LoginController::class, 'loginByProvider']);

    $app->group('', function () use ($app) {
        $app->post('/wallet', [WalletController::class, 'store']);
    })->add(new JWTMiddleware());
});
