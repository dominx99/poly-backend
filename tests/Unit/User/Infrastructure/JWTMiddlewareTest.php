<?php declare(strict_types = 1);

namespace Tests\Unit\User\Infrastructure;

use Firebase\JWT\JWT;
use Ramsey\Uuid\Uuid;
use Tests\BaseTestCase;
use Tests\DatabaseTrait;
use App\System\Infrastructure\StatusMessage;
use App\User\Infrastructure\Middleware\JWTMiddleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class JWTMiddlewareTest extends BaseTestCase
{
    use DatabaseTrait;

    public function setUp(): void
    {
        parent::setUp();

        $this->app->get('/token', function (RequestInterface $request, ResponseInterface $response) {
            $response->getBody()->write('authorized');

            return $response;
        })->add(new JWTMiddleware());
    }

    /** @test */
    public function can_access_with_token()
    {
        $id = Uuid::uuid4();
        $this->createUser($id, 'example@test.com', 'secret');

        $token = JWT::encode(['id' => $id], getenv('JWT_KEY'));

        $request = $this->request([
            'method' => 'GET',
            'uri'    => '/token',
        ]);

        $request = $request->withHeader('Authorization', "Bearer {$token}");

        $response = $this->app->handle($request);

        $this->assertEquals('authorized', (string) $response->getBody());
    }

    /** @test */
    public function that_invalid_token_signature_will_catch_error()
    {
        $token = JWT::encode(['id' => 'abc'], 'wrong_key');

        $request = $this->request([
            'method' => 'GET',
            'uri'    => '/token',
        ]);

        $request = $request->withHeader('Authorization', "Bearer {$token}");

        $response = $this->app->handle($request);

        $this->assertEquals(400, $response->getStatusCode());

        $body = json_decode((string) $response->getBody(), true);

        $this->assertEquals(StatusMessage::FAIL, $body['status']);
        $this->assertEquals('Signature verification failed', $body['error']);
    }

    /** @test */
    public function that_expired_token_will_return_exception()
    {
        $token = JWT::encode([
            'id'  => 'xyz',
            'exp' => time() - 3600,
        ], getenv('JWT_KEY'));

        $request = $this->request([
            'method' => 'GET',
            'uri'    => '/token',
        ]);

        $request = $request->withHeader('Authorization', "Bearer {$token}");

        $response = $this->app->handle($request);

        $this->assertEquals(400, $response->getStatusCode());

        $body = json_decode((string) $response->getBody(), true);

        $this->assertEquals(StatusMessage::FAIL, $body['status']);
        $this->assertEquals('Expired token', $body['error']);
    }

    /** @test */
    public function that_before_valid_token_will_return_exception()
    {
        $token = JWT::encode([
            'id'  => 'xyz',
            'nbf' => time() + 3600,
        ], getenv('JWT_KEY'));

        $request = $this->request([
            'method' => 'GET',
            'uri'    => '/token',
        ]);

        $request = $request->withHeader('Authorization', "Bearer {$token}");

        $response = $this->app->handle($request);

        $this->assertEquals(400, $response->getStatusCode());

        $body = json_decode((string) $response->getBody(), true);

        $this->assertEquals(StatusMessage::FAIL, $body['status']);
        $this->assertStringContainsString('Cannot handle token prior to', $body['error']);
    }

    /** @test */
    public function that_empty_token_will_return_error()
    {
        $request = $this->request([
            'method' => 'GET',
            'uri'    => '/token',
        ]);

        $request = $request->withHeader('Authorization', '');

        $response = $this->app->handle($request);

        $this->assertEquals(400, $response->getStatusCode());

        $body = json_decode((string) $response->getBody(), true);

        $this->assertEquals(StatusMessage::FAIL, $body['status']);
    }

    /** @test */
    public function that_not_providing_token_will_return_error()
    {
        $response = $this->runApp('GET', '/token');

        $this->assertEquals(400, $response->getStatusCode());

        $body = json_decode((string) $response->getBody(), true);

        $this->assertEquals(StatusMessage::FAIL, $body['status']);
        $this->assertEquals(StatusMessage::TOKEN_REQUIRED, $body['error']);
    }
}
