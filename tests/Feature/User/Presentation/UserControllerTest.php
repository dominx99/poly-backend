<?php declare(strict_types = 1);

namespace Tests\Feature\User\Presentation;

use Tests\BaseTestCase;
use Tests\DatabaseTrait;
use Ramsey\Uuid\Uuid;
use App\System\Infrastructure\JWT;
use App\System\Infrastructure\StatusCode;
use App\System\Infrastructure\StatusMessage;

class UserControllerTest extends BaseTestCase
{
    use DatabaseTrait;

    /** @test */
    public function that_shows_valid_informations()
    {
        $id = Uuid::uuid4();

        $this->createUser($id, 'example-test@test.com', 'test123');
        $this->token = JWT::encode(['id' => $id], getenv('JWT_KEY'));

        $response = $this->runApp('GET', '/api/user');

        $this->assertEquals(200, $response->getStatusCode());

        $body = json_decode((string) $response->getBody(), true);

        $this->assertEquals('example-test@test.com', $body['data']['email']);
    }

    /** @test */
    public function that_returns_valid_error_when_user_does_not_exists()
    {
        $this->token = JWT::encode(['id' => 'not-existing-user-id'], getenv('JWT_KEY'));

        $response = $this->runApp('GET', '/api/user');

        $this->assertEquals(StatusCode::HTTP_NOT_FOUND, $response->getStatusCode());

        $body = json_decode((string) $response->getBody(), true);
        $this->assertEquals(StatusMessage::USER_NOT_FOUND, $body['error']);
    }
}
