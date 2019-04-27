<?php declare (strict_types = 1);

namespace Wallet\User\Application;

use Wallet\System\Application\Validation\Validator;

class UserStoreValidator extends Validator
{
    public function __construct()
    {
        $this->extendRules([
            'email' => v::required(),
        ]);
    }

    /**
     * @return void
     */
    public function extendPasswordRule(): void
    {
        $this->extendRule(
            'password',
            v::required()
        );
    }
}
