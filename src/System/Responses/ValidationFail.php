<?php declare (strict_types = 1);

namespace Wallet\System\Responses;

use Slim\Http\StatusCode;
use Wallet\System\Responses\Fail;

class ValidationFail extends Fail
{
    /**
     * @param array $errors
     */
    public function __construct(array $errors)
    {
        parent::__construct(['errors' => $errors], StatusCode::HTTP_BAD_REQUEST);
    }
}
