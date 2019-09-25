<?php declare(strict_types = 1);

namespace App\Army\Domain;

use \Doctrine\ORM\Mapping as ORM;
use App\Map\Domain\Map\Unit;

/**
 * @ORM\Entity
 */
class ArmyUnit extends Unit
{
    /**
     * @var array
     */
    const DEFAULT_ARMIES = [
        [
            'name'         => 'donatello',
            'display_name' => 'Donatello',
            'cost'         => 100,
            'power'        => 1,
            'defense'      => 1,
        ],
        [
            'name'         => 'michelangelo',
            'display_name' => 'Michelangelo',
            'cost'         => 300,
            'power'        => 2,
            'defense'      => 2,
        ],
        [
            'name'         => 'raphael',
            'display_name' => 'Raphael',
            'cost'         => 900,
            'power'        => 3,
            'defense'      => 3,
        ],
        [
            'name'         => 'leonardo',
            'display_name' => 'Leonardo',
            'cost'         => 2700,
            'power'        => 4,
            'defense'      => 3,
        ],
    ];
}
