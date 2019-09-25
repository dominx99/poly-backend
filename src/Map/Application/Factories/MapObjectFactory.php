<?php declare(strict_types=1);

namespace App\Map\Application\Factories;

use App\Map\Domain\Map\MapObject;
use App\Map\Domain\Map\Army;
use App\System\Infrastructure\Exceptions\UnexpectedException;
use Ramsey\Uuid\Uuid;

final class MapObjectFactory
{
    /**
     * @param string $type
     * @return \App\Map\Domain\Map\MapObject
     */
    public static function create(string $type): MapObject
    {
        $type = 'create' . ucfirst($type);

        if (! method_exists(self::class, $type)) {
            throw new UnexpectedException(sprintf('Method %s does not exists.', $type));
        }

        return self::$type();
    }

    /**
     * @return \App\Map\Domain\Map\Army
     */
    public static function createArmy(): Army
    {
        return new Army((string) Uuid::uuid4());
    }
}
