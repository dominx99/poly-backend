<?php declare(strict_types=1);

namespace App\Map\Infrastructure;

use App\Map\Exceptions\UnexpectedPositionException;

final class Position
{
    /**
     * @var int
     */
    private $x;

    /**
     * @var int
     */
    private $y;

    /**
     * @param int $x
     * @param int $y
     */
    public function __construct(int $x, int $y)
    {
        if ($x < 0 || $y < 0) {
            throw new UnexpectedPositionException('Cannot declare position less than 0');
        }

        $this->x = $x;
        $this->y = $y;
    }

    /**
     * @return int
     */
    public function getXPos(): int
    {
        return $this->x;
    }

    /**
     * @return int
     */
    public function getYPos(): int
    {
        return $this->y;
    }

    /**
     * @return void
     */
    public function moveRandom(): void
    {
        $directions = ['left', 'right', 'up', 'down'];

        $direction = $directions[array_rand($directions)];

        $this->$direction();
    }

    /**
     * @param \App\Map\Infrastructure\Position $position
     * @return bool
     */
    public function is(Position $position): bool
    {
        return $this->x == $position->x && $this->y == $position->y;
    }

    /**
     * @return self
     */
    public function clone(): self
    {
        return new self($this->x, $this->y);
    }

    /**
     * @return void
     */
    private function left(): void
    {
        $this->x -= 1;
    }

    /**
     * @return void
     */
    private function right(): void
    {
        $this->x += 1;
    }

    /**
     * @return void
     */
    private function up(): void
    {
        $this->y -= 1;
    }

    /**
     * @return void
     */
    private function down(): void
    {
        $this->y += 1;
    }
}
