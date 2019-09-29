<?php declare(strict_types=1);

namespace Tests\Unit\World\Http\Middleware;

use App\System\Infrastructure\StatusCode;
use App\System\Infrastructure\StatusMessage;
use App\User\Contracts\UserQueryRepository;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Tests\BaseTestCase;
use App\User\Application\Query\UserView;
use App\User\Infrastructure\Middleware\UserBelongToWorld;
use Ramsey\Uuid\Uuid;
use App\System\System;

final class UserBelongToWorldTest extends BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->app->get('/api/{worldId}/test', function (): ResponseInterface {
            $response = ResponseFactory::createResponse(200);
            $response->getBody()->write('Success');

            return $response;
        })->addMiddleware(new UserBelongToWorld($this->container->get(System::class)));

        $this->app->get('/api/test', function (): ResponseInterface {
            $response = ResponseFactory::createResponse(200);
            $response->getBody()->write('Success');

            return $response;
        })->addMiddleware(new UserBelongToWorld($this->container->get(System::class)));

        $this->worldId = (string) Uuid::uuid4();

        $usersRepository = $this->createMock(UserQueryRepository::class);

        $usersRepository->expects($this->exactly(2))
            ->method('find')
            ->willReturn(UserView::createFromDatabase([
                'id'       => 'test',
                'world_id' => $this->worldId,
                'email'    => 'email',
                'password' => 'password',
                'color'    => 'green',
            ]));

        $this->container->set(UserQueryRepository::class, $usersRepository);
    }

    /** @test */
    public function that_authorizes_request()
    {
        $response = $this->runApp('GET', "api/{$this->worldId}/test", [], [
            'attributes' => [
                'decodedToken' => ['id' => 'test'],
            ],
        ]);

        $this->assertSame(StatusCode::HTTP_OK, $response->getStatusCode());
        $this->assertSame('Success', (string) $response->getBody());

        $response = $this->runApp('GET', 'api/test', ['world_id' => $this->worldId], [
            'attributes' => [
                'decodedToken' => ['id' => 'test'],
            ],
        ]);

        $this->assertSame(StatusCode::HTTP_OK, $response->getStatusCode());
        $this->assertSame('Success', (string) $response->getBody());
    }

    /** @test */
    public function that_does_not_authorizes()
    {
        $wrongWorldId = (string) Uuid::uuid4();

        $response = $this->runApp('GET', 'api/' . $wrongWorldId . '/test', [], [
            'attributes' => [
                'decodedToken' => ['id' => 'test'],
            ],
        ]);

        $expectedResponse = [
            'error'  => StatusMessage::USER_NOT_BELONG_TO_WORLD,
            'status' => 'fail',
        ];

        $this->assertSame(StatusCode::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
        $this->assertSame($expectedResponse, json_decode((string) $response->getBody(), true));

        $response = $this->runApp('GET', 'api/test', ['world_id' => $wrongWorldId], [
            'attributes' => [
                'decodedToken' => ['id' => 'test'],
            ],
        ]);

        $this->assertSame(StatusCode::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
        $this->assertSame($expectedResponse, json_decode((string) $response->getBody(), true));
    }
}
