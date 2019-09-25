<?php declare(strict_types = 1);

namespace App\Map\Infrastructure;

use App\Map\Contracts\MapWriteRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Map\Contracts\MapQueryRepository;
use Ramsey\Uuid\Uuid;
use App\User\Domain\User;
use App\Map\Domain\Map as DomainMap;
use App\System\Infrastructure\Exceptions\UnexpectedException;
use App\User\Domain\Resource;
use App\User\Domain\Resource\Gold;

class ORMMaps implements MapWriteRepository
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
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
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager, MapQueryRepository $query)
    {
        $this->entityManager = $entityManager;
        $this->query         = $query;
        $this->connection    = $entityManager->getConnection();
    }

    /**
     * @param string $mapId
     * @param array $userIds
     * @param array $positions
     * @return void
     */
    public function assignPositions(string $mapId, array $userIds, array $fieldIds): void
    {
        $query = 'update fields f set f.user_id = case ';

        foreach (array_combine($fieldIds, $userIds) as $field => $user) {
            $query .= "when f.id='{$field}' then '{$user}' ";
        }

        $query .= 'end';

        $this->connection->executeQuery($query);
    }

    /**
     * @param string $mapId
     * @param array $userIds
     * @return void
     */
    public function assignResources(string $mapId, array $userIds): void
    {
        $users = $this->entityManager->getRepository(User::class)->findBy(['id' => $userIds]);
        if (! $map = $this->entityManager->getRepository(DomainMap::class)->find($mapId)) {
            throw new UnexpectedException('Map not found');
        }

        foreach ($users as $user) {
            $resource = new Resource((string) Uuid::uuid4(), Gold::createDefault());
            $resource->addUser($user);
            $resource->addMap($map);

            $this->entityManager->persist($resource);
        }

        $this->entityManager->flush();
    }
}
