<?php declare(strict_types = 1);

namespace App\User\Application\Validation;

use Respect\Validation\Validator as v;
use App\System\Application\Validation\Validator;

class ProviderAuthValidator extends Validator
{
    public function __construct()
    {
        $this->extendRule('access_token', v::notEmpty());
    }
}
