<?php declare(strict_types = 1);

namespace App\User\Application\Validation;

use App\System\Application\Validation\Validator;
use \Respect\Validation\Validator as v;
use App\User\Contracts\UserQueryRepository;

class UserStoreValidator extends Validator
{
    public function __construct(UserQueryRepository $users)
    {
        $this->extendRules([
            'email'    => v::notEmpty()->email()->EmailAvailable($users),
            'password' => v::notEmpty()->length(6, 32),
        ]);
    }
}
