<?php declare(strict_types = 1);

namespace App\Building\Domain;

use \Doctrine\ORM\Mapping as ORM;
use App\Map\Domain\Map\MapObject;

/**
 * @ORM\Entity
 */
class Building extends MapObject
{
}
