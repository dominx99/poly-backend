<?php declare(strict_types = 1);

namespace Tests\Feature\User\Presentation;

use Tests\BaseTestCase;
use Tests\DatabaseTrait;
use Ramsey\Uuid\Uuid;
use App\System\Infrastructure\JWT;

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
}
