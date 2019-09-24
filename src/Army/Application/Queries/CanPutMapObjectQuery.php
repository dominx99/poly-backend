<?php declare(strict_types=1);

namespace App\Army\Application\Queries;

use App\Army\Application\Commands\CanPutMapObject;
use App\Map\Contracts\FieldQueryRepository;
use Doctrine\ORM\EntityManagerInterface;

final class CanPutMapObjectQuery
{
    const DEFAULT_RANGE = 1;

    /**
     * @var \Doctrine\DBAL\Connection
     */
    private $connection;

    /**
     * @var \App\Map\Contracts\FieldQueryRepository
     */
    private $fields;

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $em
     */
    public function __construct(
        EntityManagerInterface $em,
        FieldQueryRepository $fields
    ) {
        $this->connection = $em->getConnection();
        $this->fields = $fields;
    }

    /**
     * @param \App\Army\Application\Commands\CanPutMapObject $command
     * @param bool
     */
    public function execute(CanPutMapObject $command): bool
    {
        $this->command = $command;

        if (! $this->isInRange(self::DEFAULT_RANGE)) {
            throw new \Exception('Field is not in your range.');
        }

        if ($this->userHasMapObject()) {
            throw new \Exception('You already have map object there.');
        }

        if (! $this->hasEnoughPower($command->power())) {
            throw new \Exception("You don't have enough power.");
        }

        return true;
    }

    /**
     * @param int $range
     * @return bool
     */
    private function isInRange(int $range): bool
    {
        $selectedField = $this->fields->find($this->command->fieldId());
        $fields = $this->fields->findByMap($this->command->mapId());
        $result = false;

        array_walk($fields, function ($field) use ($range, $selectedField, &$result) {
            if ($selectedField->inRange($field, $range)) {
                $result = true;
            }
        });

        return $result;
    }

    /**
     * @return bool
     */
    private function userHasMapObject(): bool
    {
        $qb = $this->connection
            ->createQueryBuilder()
            ->select('*')
            ->from('map_objects', 'mo')
            ->where('mo.field_id = :fieldId')
            ->where('mo.user_id = :userId')
            ->where('mo.map_id = :mapId')
            ->setParameters([
                'fieldId' => $this->command->fieldId(),
                'userId'  => $this->command->userId(),
                'mapId'   => $this->command->mapId(),
            ]);

        if (! $this->connection->fetchAssoc($qb->getSQL(), $qb->getParameters())) {
            return false;
        }

        return true;
    }

    private function hasEnoughPower(): bool
    {
        $power = $this->connection
            ->createQueryBuilder()
            ->select('u.power')
            ->from('units', 'u')
            ->where('unitable_id = :unitableId')
            ->setParameter('unitableId', $this->command->unitId());

        $qb = $this->connection
            ->createQueryBuilder()
            ->select('mo.defense')
            ->from('map_objects', 'mo')
            ->where('mo.field_id = :fieldId')
            ->where('mo.map_id = :mapId')
            ->setParameters([
                'fieldId' => $this->command->fieldId(),
                'mapId'   => $this->command->mapId(),
            ]);

        if ($defense = $this->connection->executeQuery($qb->getSQL(), $qb->getParameters())) {
            return $this->command->power() > $defense;
        }

        return true;
    }
}
