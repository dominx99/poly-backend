<?php declare(strict_types = 1);

namespace App\Building\Domain;

use \Doctrine\ORM\Mapping as ORM;
use App\Map\Domain\Map\Unit;

/**
 * @ORM\Entity
 */
class BuildingUnit extends Unit
{
    /**
     * @var array
     */
    const DEFAULT_BUILDINGS = [
        [
            'name'           => 'factory',
            'display_name'   => 'Factory',
            'cost'           => 150,
            'power'          => 0,
            'defense'        => 1,
            'earning_points' => 5,
        ],
    ];
}
