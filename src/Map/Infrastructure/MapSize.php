<?php declare(strict_types=1);

namespace App\Map\Infrastructure;

final class MapSize
{
    /**
     * @var int
     */
    private $width;

    /**
     * @var int
     */
    private $height;

    /**
     * @param int $width
     * @param int $height
     */
    public function __construct(int $width, int $height)
    {
        $this->width  = $width;
        $this->height = $height;
    }

    /**
     * @return int
     */
    public function countFields(): int
    {
        return $this->width * $this->height;
    }

    /**
     * @param \App\Map\Infrastructure\Position $position
     * @return bool
     */
    public function outOfBorder(Position $position): bool
    {
        return
            $position->getXPos() < 0 ||
            $position->getXPos() >= $this->width ||
            $position->getYPos() < 0 ||
            $position->getYPos() >= $this->height;
    }

    /**
     * @return \App\Map\Infrastructure\Position
     */
    public function getCenterPosition(): Position
    {
        return new Position(
            $this->getCenterXPosition(),
            $this->getCenterYPosition()
        );
    }

    /**
     * @return int
     */
    public function getCenterXPosition(): int
    {
        return (int) round($this->width / 2);
    }

    /**
     * @return int
     */
    public function getCenterYPosition(): int
    {
        return (int) round($this->height / 2);
    }
}
