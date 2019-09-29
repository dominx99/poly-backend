<?php declare(strict_types=1);

namespace App\User\Application\Queries;

use App\System\Infrastructure\Exceptions\UnexpectedException;
use App\User\Application\Commands\GetPlayers;
use Doctrine\ORM\EntityManagerInterface;
use App\User\Application\Query\UserView;
use App\System\Contracts\Responsable;
use App\System\Responses\Success;

final class GetPlayersQuery
{
    /** @var \Doctrine\DBAL\Connection */
    private $connection;

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->connection = $entityManager->getConnection();
    }

    /**
     * @param \App\User\Application\Commands\GetPlayers $command
     * @return void
     */
    public function execute(GetPlayers $command): Responsable
    {
        $qb = $this->connection
            ->createQueryBuilder()
            ->select('u.*')
            ->from('users', 'u')
            ->where('u.world_id = :worldId')
            ->setParameter('worldId', $command->worldId());

        if (! $players = $this->connection->fetchAll($qb->getSQL(), $qb->getParameters())) {
            throw new UnexpectedException('Players not found.');
        }

        return new Success(array_map(function ($player) {
            $player = UserView::createFromDatabase($player);

            return [
                'id'    => $player->id(),
                'color' => $player->color(),
            ];
        }, $players));
    }
}
