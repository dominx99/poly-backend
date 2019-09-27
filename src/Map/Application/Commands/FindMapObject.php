<?php declare(strict_types=1);

namespace App\Map\Application\Commands;

use App\System\Contracts\Command;

final class FindMapObject implements Command
{
    /** @var string */
    private $id;

    /**
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }
}
