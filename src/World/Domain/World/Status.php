<?php declare(strict_types = 1);

namespace App\World\Domain\World;

use \Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class Status
{
    const CREATED        = 'created';
    const MAP_GENERATION = 'map_generation';
    const STARTED        = 'started';

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", nullable=false, unique=false)
     */
    private $status;

    /**
     * @param string $status
     */
    public function __construct(string $status)
    {
        $this->status = $status;
    }
}
