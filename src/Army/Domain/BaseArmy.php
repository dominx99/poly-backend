<?php declare(strict_types = 1);

namespace App\Army\Domain;

use \Doctrine\ORM\Mapping as ORM;
use App\Map\Domain\Map;
use App\Army\Domain\BaseArmy\Name;
use App\Army\Domain\BaseArmy\DisplayName;
use App\Army\Domain\BaseArmy\Cost;

/**
 * @ORM\Entity
 * @ORM\Table(name="base_armies")
 */
class BaseArmy
{
    /**
     * @var array
     */
    const DEFAULT_ARMIES = [
        [
            'name'         => 'donatello',
            'display_name' => 'Donatello',
            'cost'         => 100,
        ],
        [
            'name'         => 'michelangelo',
            'display_name' => 'Michelangelo',
            'cost'         => 300,
        ],
        [
            'name'         => 'raphael',
            'display_name' => 'Raphael',
            'cost'         => 900,
        ],
        [
            'name'         => 'leonardo',
            'display_name' => 'Leonardo',
            'cost'         => 2700,
        ],
    ];

    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private $id;

    /**
     * @var \App\Map\Domain\Map
     *
     * @ORM\OneToOne(targetEntity="\App\Map\Domain\Map", inversedBy="armies")
     */
    private $map;

    /**
     * @var \App\Army\Domain\BaseArmy\Name
     *
     * @ORM\Embedded(class="\App\Army\Domain\BaseArmy\Name", columnPrefix=false)
     */
    private $name;

    /**
     * @var \App\Army\Domain\BaseArmy\DisplayName
     *
     * @ORM\Embedded(class="\App\Army\Domain\BaseArmy\DisplayName", columnPrefix=false)
     */
    private $displayName;

    /**
     * @var \App\Army\Domain\BaseArmy\Cost
     *
     * @ORM\Embedded(class="\App\Army\Domain\BaseArmy\Cost", columnPrefix=false)
     */
    private $cost;

    /**
     * @param string $id
     * @param \App\Army\Domain\BaseArmy\Name $name
     * @param \App\Army\Domain\BaseArmy\DisplayName $displayName
     * @param \App\Army\Domain\BaseArmy\Cost $cost
     */
    public function __construct(string $id, Name $name, DisplayName $displayName, Cost $cost)
    {
        $this->id          = $id;
        $this->name        = $name;
        $this->displayName = $displayName;
        $this->cost        = $cost;
    }

    /**
     * @param \App\Map\Domain\Map $map
     * @return void
     */
    public function addMap(Map $map): void
    {
        if ($this->map === $map) {
            return;
        }

        $this->map = $map;
        $map->addBaseArmy($this);
    }
}
