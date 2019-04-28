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

    public function assertDatabase(string $table, array $data): bool
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

        return (bool) $this->dbConnection->fetchAssoc($queryBuilder->getSQL(), $queryBuilder->getParameters());
    }

    public function assertDatabaseHas(string $table, array $data): void
    {
        Assert::assertTrue($this->assertDatabase($table, $data));
    }

    public function assertDatabaseMissing(string $table, array $data): void
    {
        Assert::assertFalse($this->assertDatabase($table, $data));
    }
}
