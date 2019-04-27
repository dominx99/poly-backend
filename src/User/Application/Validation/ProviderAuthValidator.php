<?php declare (strict_types = 1);

namespace Wallet\User\Application\Validation;

use Respect\Validation\Validator as v;
use Wallet\System\Application\Validation\Validator;

class ProviderAuthValidator extends Validator
{
    public function __construct()
    {
        $this->extendRule('access_token', v::notEmpty());
    }
}
