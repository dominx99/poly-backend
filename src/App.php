<?php declare(strict_types = 1);

namespace App;

use Dotenv\Dotenv;
use Slim\Factory\AppFactory;
use Slim\App as SlimApp;
use DI\Container;
use App\System\Infrastructure\CallableResolver;
use App\System\Infrastructure\Middleware\ExceptionMiddleware;

final class App
{
    const TESTING_ENV     = '.env.testing';
    const DEVELOPMENT_ENV = '.env';

    /**
     * @return \Slim\App
     */
    public static function create(): SlimApp
    {
        self::loadEnv(static::DEVELOPMENT_ENV);

        return self::configuredApp();
    }

    /**
     * @return \Slim\App
     */
    public static function createForTesting(): SlimApp
    {
        self::loadEnv(static::TESTING_ENV);

        return self::configuredApp();
    }

    /**
     * @return \Slim\App
     */
    private static function configuredApp(): SlimApp
    {
        $container = new Container();

        AppFactory::setContainer($container);
        AppFactory::setCallableResolver(new CallableResolver(new \Invoker\CallableResolver($container)));

        $app = AppFactory::create();
        $app->addRoutingMiddleware();
        $app->add(new ExceptionMiddleware());
        $app->addErrorMiddleware(true, false, false);

        return $app;
    }

    /**
     * @param string $envFilename
     * @return void
     */
    private static function loadEnv(string $envFilename): void
    {
        $dotenv = Dotenv::create(__DIR__ . '/..//', $envFilename);
        $dotenv->load();
    }
}
