<?php declare(strict_types = 1);

namespace Tests;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManager;
use Firebase\JWT\JWT;
use PHPUnit\Framework\TestCase;
use Slim\Http\Environment;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\RequestBody;
use Slim\Http\Response;
use Slim\Http\Uri;
use App\App;
use App\System\System;
use Ramsey\Uuid\Uuid;
use App\User\Infrastructure\DbalUsers;

class BaseTestCase extends TestCase
{
    protected $app;

    protected $cli;

    protected $container;

    protected $system;

    protected $entityManager;

    protected $dbConnection;

    protected $queryBuilder;

    protected $token;

    public function setUp(): void
    {
        $this->createApplication();
        $this->createCli();
        $this->createEntityManager();
        $this->users = $this->container->get(DbalUsers::class);

        $traits = array_flip(class_uses(static::class));
        if (isset($traits[DatabaseTrait::class])) {
            $this->migrate();
        }
    }

    public function tearDown(): void
    {
        $traits = array_flip(class_uses(static::class));
        if (isset($traits[DatabaseTrait::class])) {
            $this->rollback();
        }

        $this->token = null;
    }

    public function createEntityManager(): void
    {
        $this->entityManager = $this->container->get(EntityManager::class);
        $this->dbConnection  = $this->entityManager->getConnection();
    }

    public function queryBuilder(): QueryBuilder
    {
        return $this->dbConnection->createQueryBuilder();
    }

    public function createApplication(): void
    {
        $app       = App::createForTesting();
        $container = $app->getContainer();

        require __DIR__ . '/../bootstrap/dependencies.php';
        require __DIR__ . '/../routes/api.php';

        $this->app       = $app;
        $this->container = $container;
        $this->system    = new System($this->container);
    }

    public function authById(string $id): void
    {
        $this->token = JWT::encode([
            'id' => $id,
        ], getenv('JWT_KEY'));
    }

    public function auth(): void
    {
        $id         = Uuid::uuid4();
        $this->user = $this->createUser($id, 'example@test.com', 'test123');
        $this->authById((string) $id);
    }

    public function request(array $options, array $params = []): Request
    {
        $default = [
            'content_type' => 'application/json',
            'method'       => 'get',
            'uri'          => '/',
        ];

        $options = array_merge($default, $options);

        $env          = Environment::mock();
        $uri          = Uri::createFromString($options['uri']);
        $headers      = Headers::createFromEnvironment($env);
        $cookies      = [];
        $serverParams = $env->all();
        $body         = new RequestBody();

        $request = new Request($options['method'], $uri, $headers, $cookies, $serverParams, $body);
        $request = $request->withParsedBody($params);
        $request = $request->withHeader('Content-Type', $options['content_type']);
        $request = $request->withMethod($options['method']);

        if ($this->token) {
            $request = $request->withHeader('Authorization', "Bearer {$this->token}");
        }

        return $request;
    }

    public function runApp(string $method, string $uri, array $params = [], array $options = [])
    {
        $options['method'] = $method;
        $options['uri']    = $uri;

        $request = $this->request($options, $params);

        return $this->app->process($request, new Response());
    }

    public function createCli(): void
    {
        $this->cli = require __DIR__ . '/../bootstrap/doctrine.php';
    }

    public function executeCommand(string $command): string
    {
        $input  = new \Symfony\Component\Console\Input\StringInput("$command");
        $output = new \Symfony\Component\Console\Output\BufferedOutput();
        $input->setInteractive(false);
        $returnCode = $this->cli->doRun($input, $output);
        if ($returnCode != 0) {
            throw new \RuntimeException('Failed to execute command. ' . $output->fetch());
        }

        return $output->fetch();
    }
}
