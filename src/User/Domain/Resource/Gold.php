<?php declare(strict_types = 1);

namespace App\User\Domain\Resource;

use App\System\Infrastructure\Exceptions\BusinessException;
use App\System\Infrastructure\Exceptions\UnexpectedException;
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
     * @throws \App\System\Infrastructure\Exceptions\UnexpectedException
     */
    public function __construct(int $gold)
    {
        if ($gold < 0) {
            throw new UnexpectedException('Gold can not be less than 0.');
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
     * @param int $amount
     * @return void
     */
    public function reduce(int $amount): void
    {
        $this->gold -= $amount;
    }

    /**
     * @param int $amount
     * @return void
     * @throws \App\System\Infrastructure\Exceptions\BusinessException
     */
    public function increase(int $amount): void
    {
        if ($amount < 0) {
            throw new BusinessException('Increase amount cannot be less than 0');
        }

        $this->gold += $amount;
    }

    /**
     * @param int $cost
     * @return void
     * @throws \App\System\Infrastructure\Exceptions\BusinessException
     */
    public function buy(int $cost): void
    {
        if ($cost < 0) {
            throw new BusinessException('Cost cannot be less than 0');
        }

        if (($this->gold - $cost) < 0) {
            throw new BusinessException('You do not afford to buy this.');
        }

        $this->reduce($cost);
    }
}
