<?php declare(strict_types = 1);

namespace Tests\Feature\World\Presentation;

use Tests\BaseTestCase;
use App\World\Domain\World;
use App\World\Domain\World\Status;
use Ramsey\Uuid\Uuid;
use App\System\Infrastructure\StatusMessage;
use Tests\DatabaseTrait;
use App\User\Domain\User;
use App\User\Domain\User\Email;

class WorldControllerTest extends BaseTestCase
{
    use DatabaseTrait;

    /** @test */
    public function that_gets_valid_world()
    {
        $worldId = (string) Uuid::uuid4();
        $userId  = Uuid::uuid4();

        $world = new World($worldId, new Status(Status::MAP_GENERATION));
        $user  = new User($userId, new Email('example@test.com'));
        $world->addUser($user);

        $this->entityManager->persist($user);
        $this->entityManager->persist($world);

        $this->entityManager->flush();

        $this->authById((string) $userId);

        $response = $this->runApp('GET', "/api/world/{$worldId}");

        $this->assertEquals(200, $response->getStatusCode());

        $body = json_decode((string) $response->getBody(), true);

        $this->assertEquals(Status::MAP_GENERATION, $body['data']['world']['status']);
    }

    /** @test */
    public function that_shows_error_when_not_found()
    {
        $id = (string) Uuid::uuid4();

        $this->auth();

        $world = new World($id, new Status(Status::MAP_GENERATION));

        $this->entityManager->persist($world);
        $this->entityManager->flush();

        $response = $this->runApp('GET', '/api/world/' . (string) Uuid::uuid4());

        $this->assertEquals(422, $response->getStatusCode());

        $body = json_decode((string) $response->getBody(), true);

        $this->assertEquals(StatusMessage::USER_NOT_BELONG_TO_WORLD, $body['error']);
    }
}
