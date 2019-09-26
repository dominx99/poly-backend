<?php declare(strict_types = 1);

namespace Tests;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Firebase\JWT\JWT;
use PHPUnit\Framework\TestCase;
use App\App;
use App\System\System;
use Ramsey\Uuid\Uuid;
use App\User\Infrastructure\DbalUsers;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\Http\ServerRequest;
use Slim\Psr7\Factory\UriFactory;
use App\System\Infrastructure\Event\EventDispatcher;
use Psr\Log\LoggerInterface;

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

    protected $userId;

    /**
     * @var \App\User\Domain\User
     */
    protected $user;

    public function setUp(): void
    {
        $this->createApplication();
        $this->createCli();
        $this->createEntityManager();
        $this->users = $this->container->get(DbalUsers::class);

        $traits = array_flip(class_uses(static::class));
        if (isset($traits[DatabaseTrait::class])) {
            $this->rollback();
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
        $this->entityManager = $this->container->get(EntityManagerInterface::class);
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
        $this->events    = new EventDispatcher($this->container, $this->container->get(LoggerInterface::class));
    }

    public function authById(string $id): void
    {
        $this->token = JWT::encode([
            'id' => $id,
        ], getenv('JWT_KEY'));
    }

    public function auth(): void
    {
        $id           = Uuid::uuid4();
        $this->userId = $id;
        $this->user   = $this->createUser($id, 'example@test.com', 'test123');
        $this->authById((string) $id);
    }

    public function request(array $options, array $params = []): ServerRequest
    {
        $uriFactory = new UriFactory();

        $default = [
            'content_type' => 'application/json',
            'method'       => 'get',
            'uri'          => '/',
        ];

        $options = array_merge($default, $options);

        $request = ServerRequestCreatorFactory::create();
        $request = $request->createServerRequestFromGlobals();

        $request = $request->withUri($uriFactory->createUri($options['uri']));
        $request = $request->withMethod($options['method']);
        $request = $request->withParsedBody($params);
        $request = $request->withHeader('Content-Type', $options['content_type']);

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

        return $this->app->handle($request);
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
