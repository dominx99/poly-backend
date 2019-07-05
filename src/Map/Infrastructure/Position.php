<?php declare(strict_types=1);

namespace App\Map\Infrastructure;

final class Position
{
    private $x;
    private $y;

    public function __construct(int $x, int $y)
    {
        if ($x < 0 || $y < 0) {
            throw new \Exception("Cannot declare position less than 0");
        }

        $this->x = $x;
        $this->y = $y;
    }

    public function getXPos(): int
    {
        return $this->x;
    }

    public function getYPos(): int
    {
        return $this->y;
    }

    public function moveRandom(): void
    {
        $directions = ['left', 'right', 'up', 'down'];

        $direction = $directions[array_rand($directions)];

        $this->$direction();
    }

    public function is(Position $position): bool
    {
        return $this->x == $position->x && $this->y == $position->y;
    }

    public function clone(): self
    {
        return new self($this->x, $this->y);
    }

    private function left(): void
    {
        $this->x -= 1;
    }

    private function right(): void
    {
        $this->x += 1;
    }

    private function up(): void
    {
        $this->y -= 1;
    }

    private function down(): void
    {
        $this->y += 1;
    }
}
