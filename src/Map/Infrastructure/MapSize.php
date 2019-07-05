<?php declare(strict_types=1);

namespace App\Map\Infrastructure;

final class MapSize
{
    private $width;

    private $height;

    public function __construct(int $width, int $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    public function countFields(): int
    {
        return $this->width * $this->height;
    }

    public function outOfBorder(Position $position): bool
    {
        return
            $position->getXPos() < 0 ||
            $position->getXPos() >= $this->width ||
            $position->getYPos() < 0 ||
            $position->getYPos() >= $this->height;
    }

    public function getCenterPosition(): Position
    {
        return new Position(
            $this->getCenterXPosition(),
            $this->getCenterYPosition()
        );
    }

    public function getCenterXPosition(): int
    {
        return (int) round($this->width / 2);
    }

    public function getCenterYPosition(): int
    {
        return (int) round($this->height / 2);
    }
}

