<?php

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;

$container = $app->getContainer();

$appConfig = require 'config/app.php';
$container->set('appConfig', $appConfig);

$container->set(\Wallet\User\Application\LoginStandard::class, Di\autowire(\Wallet\User\Application\LoginStandardHandler::class));
$container->set(\Wallet\User\Application\RegisterStandard::class, Di\autowire(\Wallet\User\Application\RegisterStandardHandler::class));

$container->set(EntityManager::class, function () use ($container) {
    $appConfig = $container->get('appConfig');

    $config = Setup::createAnnotationMetadataConfiguration(
        [$appConfig['orm']['entities_dir']],
        $appConfig['app']['dev']
    );

    $config->setMetadataDriverImpl(
        new AnnotationDriver(
            new AnnotationReader(),
            $appConfig['orm']['entities_dir']
        )
    );

    return EntityManager::create(
        $appConfig['db'],
        $config
    );
});

$container->set(\Wallet\User\Infrastructure\DbalUsers::class, DI\autowire(\Wallet\User\Infrastructure\DbalUsers::class));

if (!\Doctrine\DBAL\Types\Type::hasType('uuid')) {
    \Doctrine\DBAL\Types\Type::addType('uuid', \Ramsey\Uuid\Doctrine\UuidType::class);
}
