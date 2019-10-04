<?php declare(strict_types=1);

namespace App\Resource\Infrastructure;

use App\System\Infrastructure\Exceptions\BusinessException;
use App\Map\Application\Query\MapObjectView;
use App\System\Contracts\GoldIncomeCalculator;

final class GoldMapObjectsIncomeCalculator implements GoldIncomeCalculator
{
    /** @var array */
    private $mapObjects;

    /** @var int */
    private $conversion;

    /**
     * @param array $mapObjects
     * @return self
     */
    public function from(array $mapObjects): self
    {
        $this->setMapObjects($mapObjects);

        return $this;
    }

    /**
     * @param int $conversion
     * @return self
     */
    public function withConversion(int $conversion): self
    {
        $this->setConversion($conversion);

        return $this;
    }

    /**
     * @return int
     */
    public function calculate(): int
    {
        $amount = 0;

        foreach ($this->mapObjects as $mapObject) {
            $amount += $this->calculateIncome($mapObject);
        }

        return $amount;
    }

    /**
     * @param \App\Map\Application\Query\MapObjectView $mapObject
     * @return int
     */
    private function calculateIncome(MapObjectView $mapObject): int
    {
        return
            $mapObject->earnedAtInSeconds() *
            $this->calculateEarningPointsToGold($mapObject->unit()->earningPoints());
    }

    private function calculateEarningPointsToGold(int $earningPoints): int
    {
        return $earningPoints * $this->conversion;
    }

    /**
     * @param array $mapObjects
     * @return void
     */
    private function setMapObjects(array $mapObjects): void
    {
        $this->mapObjects = $mapObjects;
    }

    /**
     * @param int $conversion
     * @return void
     */
    private function setConversion(int $conversion): void
    {
        if ($conversion <= 0) {
            throw new BusinessException('Conversion cannot be 0 or less');
        }

        $this->conversion = $conversion;
    }
}
