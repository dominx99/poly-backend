<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\Migrations\Configuration\Configuration;
use Doctrine\Migrations\Tools\Console\Helper\ConfigurationHelper;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\QuestionHelper;

if (!getenv('APP_NAME')) {
    $dotenv = \Dotenv\Dotenv::create(__DIR__ . '/..//');
    $dotenv->load();
}

$dbParams = [
    'dbname'   => getenv('DB_NAME'),
    'user'     => getenv('DB_USER'),
    'password' => getenv('DB_PASSWORD'),
    'host'     => getenv('DB_HOST'),
    'driver'   => getenv('DB_DRIVER'),
];

$connection = DriverManager::getConnection($dbParams);

$configuration = new Configuration($connection);
$configuration->setMigrationsNamespace('Migrations');
$configuration->setMigrationsTableName('migrations');
$configuration->setMigrationsColumnName('version');
$configuration->setMigrationsDirectory(__DIR__ . '/../database/migrations');
$configuration->setCustomTemplate(__DIR__ . '/../database/MigrationTemplate.tpl');

$helperSet = new HelperSet();
$helperSet->set(new QuestionHelper(), 'question');
$helperSet->set(new ConnectionHelper($connection), 'db');
$helperSet->set(new ConfigurationHelper($connection, $configuration));

$cli = new Application('Doctrine Migrations');
$cli->setCatchExceptions(true);
$cli->setHelperSet($helperSet);

$cli->addCommands([
    new \Doctrine\Migrations\Tools\Console\Command\ExecuteCommand(),
    new \Doctrine\Migrations\Tools\Console\Command\GenerateCommand(),
    new \Doctrine\Migrations\Tools\Console\Command\LatestCommand(),
    new \Doctrine\Migrations\Tools\Console\Command\MigrateCommand(),
    new \Doctrine\Migrations\Tools\Console\Command\RollupCommand(),
    new \Doctrine\Migrations\Tools\Console\Command\StatusCommand(),
    new \Doctrine\Migrations\Tools\Console\Command\VersionCommand(),
]);

return $cli;
