<?php

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;

$container = $app->getContainer();

$appConfig = require 'config/app.php';
$container->set('appConfig', $appConfig);

$container->set(
    \App\User\Application\LoginStandard::class,
    Di\autowire(\App\User\Application\LoginStandardHandler::class)
);

$container->set(
    \App\User\Application\RegisterStandard::class,
    Di\autowire(\App\User\Application\RegisterStandardHandler::class)
);

$container->set(
    \App\User\Application\LoginSocial::class,
    Di\autowire(\App\User\Application\LoginSocialHandler::class)
);

$container->set(
    \App\User\Application\RegisterSocial::class,
    Di\autowire(\App\User\Application\RegisterSocialHandler::class)
);

$container->set(
    \App\User\Application\FindUserByEmail::class,
    Di\autowire(\App\User\Application\FindUserByEmailHandler::class)
);

$container->set(
    \App\User\Application\GetSocialUserByAccessTokenAndProvider::class,
    Di\autowire(\App\User\Application\GetSocialUserByAccessTokenAndProviderHandler::class)
);

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

$container->set(
    \App\User\Infrastructure\DbalUsers::class,
    DI\autowire(\App\User\Infrastructure\DbalUsers::class)
);

if (!\Doctrine\DBAL\Types\Type::hasType('uuid')) {
    \Doctrine\DBAL\Types\Type::addType('uuid', \Ramsey\Uuid\Doctrine\UuidType::class);
}

$container->set(\Overtrue\Socialite\SocialiteManager::class, new \Overtrue\Socialite\SocialiteManager(
    $container->get('appConfig')['auth']
));
