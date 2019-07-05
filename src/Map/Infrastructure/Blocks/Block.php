<?php declare(strict_types=1);

namespace App\Map\Infrastructure\Blocks;

abstract class Block
{
    public function get()
    {
        return $this->type;
    }
}
