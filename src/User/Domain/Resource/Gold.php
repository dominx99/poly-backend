<?php declare(strict_types = 1);

namespace App\User\Domain\Resource;

use \Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class Gold
{
    /**
     * @var int
     *
     * @ORM\Column(name="gold", type="integer", nullable=false, unique=true)
     */
    private $gold;

    /**
     * @param int $gold
     */
    public function __construct(int $gold)
    {
        $this->gold = $gold;
    }

    public function createDefault(): self
    {
        return new static(300);
    }
}
