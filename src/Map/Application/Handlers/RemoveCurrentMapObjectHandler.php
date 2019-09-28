<?php declare(strict_types=1);

namespace App\Map\Application\Handlers;

use App\Map\Application\Commands\RemoveCurrentMapObject;
use App\System\Infrastructure\Exceptions\UnexpectedException;
use Doctrine\ORM\EntityManagerInterface;
use App\Map\Domain\Map\MapObject;

final class RemoveCurrentMapObjectHandler
{
	/** @var \Doctrine\ORM\EntityManagerInterface */
	private $entityManager;

	/**
	 * @param \Doctrine\ORM\EntityManagerInterface $entityManager
	 */
    public function __construct(EntityManagerInterface $entityManager)
    {
		$this->entityManager = $entityManager;
    }

	/**
	 * @param \App\Map\Application\Commands\RemoveCurrentMapObject $command
	 * @return void
	 */
    public function handle(RemoveCurrentMapObject $command): void
    {
        if (! $mapObject = $this->entityManager->getRepository(MapObject::class)->findOneBy([
            'field' => $command->fieldId()
        ])) {
            return;
        }

        $this->entityManager->remove($mapObject);
        $this->entityManager->flush();
    }
}
