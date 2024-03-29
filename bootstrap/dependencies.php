<?php

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;
use \Respect\Validation\Validator as v;

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
    \App\World\Application\GetWorlds::class,
    Di\autowire(\App\World\Application\GetWorldsHandler::class)
);

$container->set(
    \App\World\Application\CreateWorld::class,
    Di\autowire(\App\World\Application\CreateWorldHandler::class)
);

$container->set(
    \App\World\Application\UserJoinWorld::class,
    Di\autowire(\App\World\Application\UserJoinWorldHandler::class)
);

$container->set(
    \App\World\Application\IsWorldReady::class,
    Di\autowire(\App\World\Application\IsWorldReadyHandler::class)
);

$container->set(
    \App\User\Application\GetSocialUserByAccessTokenAndProvider::class,
    Di\autowire(\App\User\Application\GetSocialUserByAccessTokenAndProviderHandler::class)
);

$container->set(
    \App\Map\Application\AssignUserPositions::class,
    Di\autowire(\App\Map\Application\AssignUserPositionsHandler::class)
);

$container->set(
    \App\World\Application\GetWorldPossibleToJoin::class,
    Di\autowire(\App\World\Application\GetWorldPossibleToJoinHandler::class)
);

$container->set(
    \App\User\Application\AlreadyInGame::class,
    Di\autowire(\App\User\Application\AlreadyInGameHandler::class)
);

$container->set(
    \App\User\Application\FindUser::class,
    Di\autowire(\App\User\Application\FindUserHandler::class)
);

$container->set(
    \App\World\Application\FindWorld::class,
    Di\autowire(\App\World\Application\FindWorldHandler::class)
);

$container->set(
    \App\Map\Application\AssignUserResources::class,
    Di\autowire(\App\Map\Application\AssignUserResourcesHandler::class)
);

$container->set(
    \App\World\Application\UpdateWorldStatus::class,
    Di\autowire(\App\World\Application\UpdateWorldStatusHandler::class)
);

$container->set(
    \App\Map\Application\FindMap::class,
    Di\autowire(\App\Map\Application\FindMapHandler::class)
);

$container->set(
    \App\User\Application\GetUserResources::class,
    Di\autowire(\App\User\Application\GetUserResourcesHandler::class)
);

$container->set(
    \App\Army\Application\Commands\AssignArmyUnits::class,
    Di\autowire(\App\Army\Application\Handlers\AssignArmyUnitsHandler::class)
);

$container->set(
    \App\Building\Application\Commands\AssignBuildingUnits::class,
    Di\autowire(\App\Building\Application\Handlers\AssignBuildingUnitsHandler::class)
);

$container->set(
    \App\Army\Application\Commands\GetArmyUnits::class,
    Di\autowire(\App\Army\Application\Queries\GetArmyUnitsHandler::class)
);

$container->set(
    \App\Map\Application\Commands\PutMapObject::class,
    Di\autowire(\App\Map\Application\Handlers\PutMapObjectHandler::class)
);

$container->set(
    \App\User\Application\Commands\ReduceUserGoldForUnit::class,
    Di\autowire(\App\User\Application\Handlers\ReduceUserGoldForUnitHandler::class)
);

$container->set(
    \App\User\Application\Commands\UserGainField::class,
    Di\autowire(\App\User\Application\Handlers\UserGainFieldHandler::class)
);

$container->set(
    \App\Map\Application\Commands\RemoveCurrentMapObject::class,
    Di\autowire(\App\Map\Application\Handlers\RemoveCurrentMapObjectHandler::class)
);

$container->set(
    \App\User\Application\Commands\AssignUserColors::class,
    Di\autowire(\App\User\Application\Handlers\AssignUserColorsHandler::class)
);

$container->set(
    \App\User\Application\Commands\UpdateUserResources::class,
    Di\autowire(\App\User\Application\Handlers\UpdateUserResourcesHandler::class)
);

$container->Set(
    \App\User\Application\Commands\CanUserAffordUnit::class,
    Di\autowire(\App\User\Application\Queries\CanUserAffordUnitQuery::class)
);

$container->set(
    \App\Map\Application\Commands\CanPutMapObject::class,
    Di\autowire(\App\Map\Application\Queries\CanPutMapObjectQuery::class)
);

$container->set(
    \App\Map\Application\Commands\FindMapObject::class,
    Di\autowire(\App\Map\Application\Queries\FindMapObjectQuery::class)
);

$container->set(
    \App\User\Application\Commands\GetPlayers::class,
    Di\autowire(\App\User\Application\Queries\GetPlayersQuery::class)
);

$container->set(
    \App\User\Application\Commands\GetUserMapObjects::class,
    Di\autowire(\App\User\Application\Queries\GetUserMapObjectsQuery::class)
);

$container->set(
    \App\World\Contracts\WorldsQueryRepository::class,
    Di\autowire(\App\World\Infrastructure\DbalWorlds::class)
);

$container->set(
    \App\World\Contracts\WorldsWriteRepository::class,
    Di\autowire(\App\World\Infrastructure\ORMWorlds::class)
);

$container->set(
    \App\Map\Contracts\MapWriteRepository::class,
    Di\autowire(\App\Map\Infrastructure\ORMMaps::class)
);

$container->set(
    \App\Map\Contracts\MapQueryRepository::class,
    Di\autowire(\App\Map\Infrastructure\DbalMaps::class)
);

$container->set(
    \App\User\Contracts\UserQueryRepository::class,
    Di\autowire(\App\User\Infrastructure\DbalUsers::class)
);

$container->set(
    \App\Army\Contracts\ArmyUnitWriteRepository::class,
    Di\autowire(\App\Army\Infrastructure\DoctrineArmyUnits::class)
);

$container->set(
    \App\Army\Contracts\ArmyUnitQueryRepository::class,
    Di\autowire(\App\Army\Infrastructure\DbalArmyUnits::class)
);

$container->set(
    \App\Map\Contracts\FieldQueryRepository::class,
    Di\autowire(\App\Map\Infrastructure\Repositories\DbalFields::class)
);

$container->set(
    \App\System\Contracts\GoldIncomeCalculator::class,
    Di\autowire(\App\Resource\Infrastructure\GoldMapObjectsIncomeCalculator::class)
);

$container->set(EntityManagerInterface::class, function () use ($container) {
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

$container->set(
    \App\System\Infrastructure\Event\EventDispatcherInterface::class,
    DI\autowire(\App\System\Infrastructure\Event\EventDispatcher::class)
);

if (!\Doctrine\DBAL\Types\Type::hasType('uuid')) {
    \Doctrine\DBAL\Types\Type::addType('uuid', \Ramsey\Uuid\Doctrine\UuidType::class);
}

$container->set(\Overtrue\Socialite\SocialiteManager::class, new \Overtrue\Socialite\SocialiteManager(
    $container->get('appConfig')['auth']
));

$container->set(\Psr\Log\LoggerInterface::class, function () {
    $log = new \Monolog\Logger('main');
    $log->pushHandler(new \Monolog\Handler\StreamHandler(__DIR__ . '/../logs/main.log'));

    return $log;
});

$container->set(\App\System\Contracts\Socket::class, function () use ($appConfig) {
    $pusher = new \Pusher\Pusher(
        $appConfig['sockets']['pusher']['key'],
        $appConfig['sockets']['pusher']['secret'],
        $appConfig['sockets']['pusher']['id'],
        [
            'cluster' => 'eu',
            'useTLS'  => true,
        ]
    );

    return new \App\System\Infrastructure\PusherSocket($pusher);
});

v::with('App\System\Application\Validation\\Rules\\');
