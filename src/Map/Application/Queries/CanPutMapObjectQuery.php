<?php declare(strict_types=1);

namespace App\Map\Application\Queries;

use App\Map\Application\Commands\CanPutMapObject;
use App\Map\Contracts\FieldQueryRepository;
use App\System\Infrastructure\Exceptions\BusinessException;
use Doctrine\DBAL\FetchMode;
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
        $this->fields     = $fields;
    }

    /**
     * @param \App\Army\Application\Commands\CanPutMapObject $command
     * @param bool
     */
    public function execute(CanPutMapObject $command): bool
    {
        $this->command = $command;

        if (! $this->isInRange(self::DEFAULT_RANGE)) {
            throw new BusinessException('Field is not in your range.');
        }

        if ($this->userHasMapObject()) {
            throw new BusinessException('You already have map object there.');
        }

        if ($this->isYourField()) {
            return true;
        }

        if (! $this->hasEnoughPower()) {
            throw new BusinessException("You don't have enough power.");
        }

        return true;
    }

    /**
     * @return bool
     */
    private function isYourField(): bool
    {
        $qb = $this->connection
            ->createQueryBuilder()
            ->select('f.user_id')
            ->from('fields', 'f')
            ->where('user_id = :userId')
            ->andWhere('f.id = :fieldId')
            ->setParameters([
                'fieldId' => $this->command->fieldId(),
                'userId'  => $this->command->userId(),
            ]);

        $stmt = $this->connection->executeQuery($qb->getSQL(), $qb->getParameters());

        return (bool) $stmt->fetchColumn();
    }

    /**
     * @param int $range
     * @return bool
     */
    private function isInRange(int $range): bool
    {
        $selectedField = $this->fields->find($this->command->fieldId());
        $fields        = $this->fields->findByMap($this->command->mapId(), $this->command->userId());
        $result        = false;

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
            ->andWhere('mo.user_id = :userId')
            ->andWhere('mo.map_id = :mapId')
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

    /**
     * @return bool
     */
    private function hasEnoughPower(): bool
    {
        return $this->getUnitPower() > $this->getFieldDefense();
    }

    /**
     * @return int
     */
    private function getUnitPower(): int
    {
        $qb = $this->connection
            ->createQueryBuilder()
            ->select('u.power')
            ->from('units', 'u')
            ->where('id = :unitId')
            ->setParameter('unitId', $this->command->unitId())
            ->execute();

        return (int) $qb->fetch(FetchMode::COLUMN) ?? 0;
    }

    /**
     * @return int
     */
    private function getFieldDefense(): int
    {
        $qb = $this->connection
            ->createQueryBuilder()
            ->select('u.defense')
            ->from('units', 'u')
            ->innerJoin('u', 'map_objects', 'mo', 'mo.unit_id = u.id')
            ->where('mo.field_id = :fieldId')
            ->setParameter('fieldId', $this->command->fieldId())
            ->execute();

        return (int) $qb->fetch(FetchMode::COLUMN) ?? 0;
    }
}
