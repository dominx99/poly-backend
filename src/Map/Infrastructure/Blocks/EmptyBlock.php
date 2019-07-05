<?php declare(strict_types=1);

namespace App\Map\Infrastructure\Blocks;

use App\Map\Contracts\Block;

final class EmptyBlock implements Block
{
    public function get(): string
    {
        return 'empty';
    }
}
