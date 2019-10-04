<?php declare(strict_types=1);

namespace App\System\Contracts;

interface IncomeCalculator
{
    /**
     * @return void
     */
    public function calculate(): int;
}
