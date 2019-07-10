<?php declare(strict_types = 1);

namespace App\World\Contracts;

interface WorldsWriteRepository
{
    public function add(array $params): void;
}
