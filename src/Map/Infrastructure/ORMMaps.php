<?php declare(strict_types = 1);

namespace App\Map\Infrastructure;

use App\Map\Contracts\MapWriteRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Map\Contracts\MapQueryRepository;
use App\User\Domain\User;
use App\Map\Domain\Map as DomainMap;
use App\Map\Domain\Map\Field;
use App\System\Infrastructure\Exceptions\UnexpectedException;
use App\User\Domain\Resource;

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
     * @param array $fieldIds
     * @return void
     */
    public function assignPositions(string $mapId, array $userIds, array $fieldIds): void
    {
        $users = $this->entityManager->getRepository(User::class)->findBy(['id' => $userIds]);
        $index = 0;

        foreach ($users as $user) {
            $user->addField(
                $this->entityManager->getRepository(Field::class)->find($fieldIds[$index++])
            );
            $this->entityManager->persist($user);
        }

        $this->entityManager->flush();
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
            $resource = Resource::createDefault();
            $resource->addUser($user);
            $resource->setMap($map);

            $this->entityManager->persist($resource);
        }

        $this->entityManager->flush();
    }
}
