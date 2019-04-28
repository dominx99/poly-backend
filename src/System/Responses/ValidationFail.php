<?php declare (strict_types = 1);

namespace Wallet\System\Responses;

use Slim\Http\Response;
use Slim\Http\StatusCode;
use Wallet\System\Contracts\Responsable;

class ValidationFail implements Responsable
{
    /**
     * @var array
     */
    private $errors;

    /**
     * @param array $errors
     */
    public function __construct(array $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @return \Slim\Http\Response
     */
    public function toResponse(): Response
    {
        return (new Response())->withJson([
            'errors' => $this->errors,
        ], StatusCode::HTTP_BAD_REQUEST);
    }
}
