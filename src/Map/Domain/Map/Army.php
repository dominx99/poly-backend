<?php declare(strict_types = 1);

namespace App\Map\Domain\Map;

use App\Army\Domain\BaseArmy;
use \Doctrine\ORM\Mapping as ORM;
use App\Map\Domain\Map\MapObject;

/**
 * @ORM\Entity
 */
class Army extends MapObject
{
    /**
     * @var \App\Army\Domain\BaseArmy
     *
     * @ORM\ManyToOne(targetEntity="\App\Army\Domain\BaseArmy", inversedBy="armies")
     * @ORM\JoinColumn(name="placable_id", referencedColumnName="id")
     */
    private $baseArmy;

    /**
     * @param \App\Army\Domain\BaseArmy $baseArmy
     * @return void
     */
    public function setBaseArmy(BaseArmy $baseArmy): void
    {
        if ($this->baseArmy === $baseArmy) {
            return;
        }

        $this->baseArmy = $baseArmy;
        $baseArmy->addArmy($this);
    }
}
