<?php declare(strict_types=1);

namespace App\Map\Contracts;

use App\Map\Application\Query\FieldView;

interface FieldQueryRepository
{
    /**
     * @param string $id
     * @throws \Exception
     * @return \App\Map\Application\Query\FieldView
     */
    public function find(string $id): FieldView;
}
