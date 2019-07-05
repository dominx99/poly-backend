<?php declare(strict_types = 1);

namespace App;

use DI\Bridge\Slim\App as DIApp;
use DI\ContainerBuilder;
use Dotenv\Dotenv;

class App extends DIApp
{
    const TESTING_ENV = '.env.testing';

    const DEVELOPMENT_ENV = '.env';

    /**
     * @param string $envFilename
     */
    public function __construct(string $envFilename)
    {
        $dotenv = Dotenv::create(__DIR__ . '/..//', $envFilename);
        $dotenv->load();

        parent::__construct();
    }

    /**
     * @return self
     */
    public static function create(): self
    {
        return new static(static::DEVELOPMENT_ENV);
    }

    /**
     * @return self
     */
    public static function createForTesting(): self
    {
        return new static(static::TESTING_ENV);
    }

    /**
     * @param  \DI\ContainerBuilder $builder
     * @return void
     */
    public function configureContainer(ContainerBuilder $builder): void
    {
        $builder->addDefinitions(__DIR__ . '/../config/app.php');
    }
}
