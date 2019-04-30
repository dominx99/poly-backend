<?php declare (strict_types = 1);

namespace Tests\Unit\User\Infrastructure;

use DateInterval;
use DateTime;
use Firebase\JWT\JWT;
use Ramsey\Uuid\Uuid;
use Slim\Http\Response;
use Tests\BaseTestCase;
use Tests\DatabaseTrait;
use Wallet\System\Infrastructure\StatusMessage;
use Wallet\User\Infrastructure\Middleware\JWTMiddleware;

class JWTMiddlewareTest extends BaseTestCase
{
    use DatabaseTrait;

    public function setUp(): void
    {
        parent::setUp();

        $this->app->get('/token', function () {
            return (new Response())->write('authorized');
        })->add(new JWTMiddleware());
    }

    /** @test */
    public function can_access_with_token()
    {
        $id = Uuid::uuid4();
        $this->createUser($id, 'example@test.com', 'secret');

        $token = JWT::encode(['id' => $id], getenv('JWT_KEY'));

        $request = $this->request([
            'method' => 'get',
            'uri'    => '/token',
        ]);

        $request = $request->withHeader('Authorization', "Bearer {$token}");

        $response = $this->app->process($request, new Response());

        $this->assertEquals('authorized', (string) $response->getBody());
    }

    /** @test */
    public function that_invalid_token_signature_will_catch_error()
    {
        $token = JWT::encode(['id' => 'abc'], 'wrong_key');

        $request = $this->request([
            'method' => 'get',
            'uri'    => '/token',
        ]);

        $request = $request->withHeader('Authorization', "Bearer {$token}");

        $response = $this->app->process($request, new Response());

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
            'exp' => (new DateTime('now'))->sub(new DateInterval('PT3M')),
        ], getenv('JWT_KEY'));

        $request = $this->request([
            'method' => 'get',
            'uri'    => '/token',
        ]);

        $request = $request->withHeader('Authorization', "Bearer {$token}");

        $response = $this->app->process($request, new Response());

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
            'method' => 'get',
            'uri'    => '/token',
        ]);

        $request = $request->withHeader('Authorization', "Bearer {$token}");

        $response = $this->app->process($request, new Response());

        $this->assertEquals(400, $response->getStatusCode());

        $body = json_decode((string) $response->getBody(), true);

        $this->assertEquals(StatusMessage::FAIL, $body['status']);
        $this->assertStringContainsString('Cannot handle token prior to', $body['error']);
    }

    /** @test */
    public function that_empty_token_will_return_error()
    {
        $request = $this->request([
            'method' => 'get',
            'uri'    => '/token',
        ]);

        $request = $request->withHeader('Authorization', "");

        $response = $this->app->process($request, new Response());

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
