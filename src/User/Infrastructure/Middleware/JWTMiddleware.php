<?php declare(strict_types = 1);

namespace App\User\Infrastructure\Middleware;

use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use UnexpectedValueException;
use App\System\Infrastructure\JWT;
use App\System\Infrastructure\StatusMessage;
use App\System\Responses\Fail;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;

class JWTMiddleware implements MiddlewareInterface
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
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
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

        $response = $handler->handle($request);

        return $response;
    }
}
