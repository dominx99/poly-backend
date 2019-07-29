<?php declare(strict_types = 1);

namespace App\System\Contracts;

interface Event
{
    public function channel(): string;

    public function name(): string;

    public function data(): array;
}
