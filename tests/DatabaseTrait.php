<?php declare (strict_types = 1);

namespace Tests;

use Doctrine\DBAL\Query\QueryBuilder;
use PHPUnit\Framework\Assert;
use Wallet\User\Domain\User;
use Wallet\User\Domain\User\Email;
use Wallet\User\Domain\User\Password;

trait DatabaseTrait
{
    public function migrate(): void
    {
        $this->executeCommand('migrate');
    }

    public function rollback(): void
    {
        $this->executeCommand('migrate first');
    }

    public function createUser($id, $email, $password): void
    {
        $user = new User(
            $id,
            new Email($email),
            new Password($password)
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function assertDatabaseHas(string $table, array $data): void
    {
        $queryBuilder = $this->queryBuilder();

        $queryBuilder
            ->select('*')
            ->from($table);

        foreach ($data as $key => $value) {
            $queryBuilder
                ->andWhere("{$key} = :{$key}")
                ->setParameter($key, $value);
        }

        $result = (bool) $this->dbConnection->fetchAssoc($queryBuilder->getSQL(), $queryBuilder->getParameters());

        Assert::assertTrue($result);
    }
}
