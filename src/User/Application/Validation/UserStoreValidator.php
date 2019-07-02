<?php declare (strict_types = 1);

namespace App\User\Application\Validation;

use App\System\Application\Validation\Validator;
use \Respect\Validation\Validator as v;

class UserStoreValidator extends Validator
{
    public function __construct()
    {
        $this->extendRules([
            'email'    => v::notEmpty()->email(),
            'password' => v::notEmpty()->length(6, 32),
        ]);
    }
}
