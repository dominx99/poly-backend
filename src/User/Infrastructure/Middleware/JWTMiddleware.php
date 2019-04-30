<?php declare (strict_types = 1);

namespace Wallet\User\Infrastructure\Middleware;

use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Slim\Http\Request;
use Slim\Http\Response;
use UnexpectedValueException;
use Wallet\System\Infrastructure\JWT;
use Wallet\System\Infrastructure\StatusMessage;
use Wallet\System\Responses\Fail;

class JWTMiddleware
{
    /**
     * @var string
     */
    private $key;

    public function __construct()
    {
        $this->key = getenv('JWT_KEY');
    }

    /**
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @param mixed $next
     * @return \Slim\Http\Response
     */
    public function __invoke(Request $request, Response $response, $next)
    {
        if (empty($request->getHeader('Authorization'))) {
            return (new Fail(['error' => StatusMessage::TOKEN_REQUIRED]))->toResponse();
        }

        try {
            $decodedToken = JWT::decodeFromBearer($request->getHeader('Authorization')[0], $this->key, ['HS256']);

            $request = $request->withAttribute('decodedToken', $decodedToken);
        } catch (SignatureInvalidException | ExpiredException | BeforeValidException | UnexpectedValueException $e) {
            return (new Fail(['error' => $e->getMessage()]))->toResponse();
        }

        return $next($request, $response);
    }
}
