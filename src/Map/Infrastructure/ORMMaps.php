<?php declare(strict_types = 1);

namespace App\Map\Infrastructure;

use App\Map\Contracts\MapWriteRepository;
use Doctrine\ORM\EntityManager;
use App\Map\Contracts\MapQueryRepository;
use Ramsey\Uuid\Uuid;
use App\User\Domain\User;
use App\Map\Domain\Map;
use App\User\Domain\Resource;
use App\User\Domain\Resource\Gold;

class ORMMaps implements MapWriteRepository
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * @var \App\Map\Contracts\MapQueryRepository
     */
    private $query;

    /**
     * @var \Doctrine\DBAL\Connection
     */
    private $connection;

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager, MapQueryRepository $query)
    {
        $this->entityManager = $entityManager;
        $this->query         = $query;
        $this->connection    = $entityManager->getConnection();
    }

    /**
     * @param string $mapId
     * @return void
     */
    public function assignPositions(string $mapId): void
    {
        $userIds  = $this->query->getUserIds($mapId);
        $fieldIds = $this->query->getRandomPositions($mapId, count($userIds));

        $query = 'update fields f set f.user_id = case ';

        foreach (array_combine($fieldIds, $userIds) as $field => $user) {
            $query .= "when f.id='{$field}' then '{$user}' ";
        }

        $query .= 'end';

        $this->connection->executeQuery($query);
    }

    /**
     * @param string $mapId
     * @return void
     */
    public function assignResources(string $mapId): void
    {
        $userIds = $this->query->getUserIds($mapId);

        $users = $this->entityManager->getRepository(User::class)->findBy(['id' => $userIds]);
        $map   = $this->entityManager->getRepository(Map::class)->find($mapId);

        foreach ($users as $user) {
            $resource = new Resource((string) Uuid::uuid4(), new Gold(300));
            $resource->addUser($user);
            $resource->addMap($map);

            $this->entityManager->persist($resource);
        }

        $this->entityManager->flush();
    }
}
