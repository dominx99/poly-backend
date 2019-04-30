<?php declare (strict_types = 1);

namespace Wallet\Wallet\Application\Validation;

use Wallet\System\Application\Validation\Validator;
use \Respect\Validation\Validator as v;

class WalletStoreValidator extends Validator
{
    public function __construct()
    {
        $this->extendRules([
            'name' => v::notEmpty()->alpha()->length(3, 16),
        ]);
    }
}
