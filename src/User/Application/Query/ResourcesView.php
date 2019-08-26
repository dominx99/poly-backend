<?php declare(strict_types = 1);

namespace App\User\Application\Query;

class ResourcesView
{
    /**
     * @var int
     */
    private $gold;

    /**
     * @param int $gold
     */
    public function __construct(int $gold)
    {
        $this->gold = $gold;
    }

    /**
     * @return int
     */
    public function gold(): int
    {
        return $this->gold;
    }

    /**
     * @param array $resource
     * @return self
     */
    public static function createFromDatabase(array $resources): self
    {
        return new static(
            (int) $resources['gold']
        );
    }
}
