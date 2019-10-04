<?php declare(strict_types=1);

namespace App\Gold\Domain;

use \Doctrine\ORM\Mapping as ORM;

/** @ORM\Embeddable */
final class EarningPoints
{
    /**
     * @var int
     *
     * @ORM\Column(name="earning_points", type="integer")
     */
    private $earningPoints;

    /**
     * @param int $earningPoints
     */
    public function __construct(int $earningPoints)
    {
        $this->earningPoints = $earningPoints;
    }
}
