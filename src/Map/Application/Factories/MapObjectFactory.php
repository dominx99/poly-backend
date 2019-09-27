<?php declare(strict_types=1);

namespace App\Map\Application\Factories;

use App\Map\Domain\Map\MapObject;
use App\Map\Domain\Map\Army;
use App\System\Infrastructure\Exceptions\UnexpectedException;

final class MapObjectFactory
{
    /**
     * @param string $id
     * @param string $type
     * @return \App\Map\Domain\Map\MapObject
     */
    public static function create(string $id, string $type): MapObject
    {
        $type = 'create' . ucfirst($type);

        if (! method_exists(self::class, $type)) {
            throw new UnexpectedException(sprintf('Method %s does not exists.', $type));
        }

        return self::$type($id);
    }

    /**
     * @return \App\Map\Domain\Map\Army
     */
    public static function createArmy(string $id): Army
    {
        return new Army($id);
    }
}
