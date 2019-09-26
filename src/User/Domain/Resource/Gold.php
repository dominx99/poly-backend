<?php declare(strict_types = 1);

namespace App\User\Domain\Resource;

use App\System\Infrastructure\Exceptions\BusinessException;
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
        if ($gold < 0) {
            throw new \Exception('Gold can not be less than 0.');
        }

        $this->gold = $gold;
    }

    /**
     * @return self
     */
    public static function createDefault(): self
    {
        return new static(300);
    }

    /**
     * @param int $cost
     * @return void
     */
    public function reduce(int $cost): void
    {
        if (($this->gold - $cost) < 0) {
            throw new BusinessException('You do not afford to buy this.');
        }

        $this->gold -= $cost;
    }
}
