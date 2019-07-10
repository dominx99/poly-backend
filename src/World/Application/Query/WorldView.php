<?php declare(strict_types = 1);

namespace App\World\Application\Query;

class WorldView
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $status;

    /**
     * @param string $id
     * @param string $status
     */
    public function __construct(string $id, string $status)
    {
        $this->id     = $id;
        $this->status = $status;
    }

    /**
     * @param array $world
     * @return self
     */
    public function createFromDatabase(array $world): self
    {
        return new static($world['id'], $world['status']);
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function status(): string
    {
        return $this->status;
    }
}
