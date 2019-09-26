<?php declare(strict_types = 1);

namespace App\World\Infrastructure;

use App\World\Contracts\WorldsWriteRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\World\Domain\World;
use App\World\Domain\World\Status;
use App\User\Domain\User;
use Ramsey\Uuid\Uuid;
use App\Map\Domain\Map;
use App\Map\Domain\Map\Field;
use App\Map\Domain\Map\Field\X;
use App\Map\Domain\Map\Field\Y;

class ORMWorlds implements WorldsWriteRepository
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \Doctrine\DBAL\Connection
     */
    private $connection;

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->connection    = $entityManager->getConnection();
    }

    /**
     * @param array $params
     * @return void
     */
    public function add(array $params): void
    {
        $id = $params['id'] ?? Uuid::uuid4();

        $world = new World(
            $id,
            new Status($params['status'])
        );

        $this->entityManager->persist($world);
        $this->entityManager->flush();
    }

    /**
     * @param string $worldId
     * @param string $userId
     * @return void
     */
    public function addUser(string $worldId, string $userId): void
    {
        $world = $this->entityManager->getRepository(World::class)->find($worldId);
        $user  = $this->entityManager->getRepository(User::class)->find($userId);

        $world->addUser($user);

        $this->entityManager->persist($world);
        $this->entityManager->flush();
    }

    /**
     * @param string $worldId
     * @param array $fields
     */
    public function addMap(string $worldId, string $mapId, array $fields): void
    {
        $world = $this->entityManager->getRepository(World::class)->find($worldId);
        $map   = new Map($mapId);

        $world->addMap($map);

        foreach ($fields as $field) {
            $field = new Field(
                (string) Uuid::uuid4(),
                new X($field['x']),
                new Y($field['y'])
            );

            $map->addField($field);
        }

        $this->entityManager->persist($map);
        $this->entityManager->persist($world);
        $this->entityManager->flush();
    }

    /**
     * @param string $worldId
     * @param string $status
     * @return void
     */
    public function updateStatus(string $worldId, string $status): void
    {
        $this->connection->createQueryBuilder()
            ->update('worlds', 'w')
            ->set('status', "'{$status}'")
            ->where('w.id = :id')
            ->setParameter('id', $worldId)
            ->execute();
    }
}
