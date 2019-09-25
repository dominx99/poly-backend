<?php declare(strict_types = 1);

namespace App\User\Contracts;

use App\User\Application\Query\UserView;

interface UserQueryRepository
{
    public function find(string $id): UserView;
}
