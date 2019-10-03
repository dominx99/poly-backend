<?php declare(strict_types=1);

namespace App\System\Infrastructure\Middleware;

use App\System\Application\Exceptions\ValidationException;
use App\System\Infrastructure\StatusCode;
use App\System\Responses\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\System\Infrastructure\Exceptions\BusinessException;
use App\System\Infrastructure\Exceptions\UnexpectedException;
use Psr\Http\Server\MiddlewareInterface;

final class ExceptionMiddleware implements MiddlewareInterface
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $response = $handler->handle($request);
        } catch (BusinessException | UnexpectedException $e) {
            return JsonResponse::create([
                'error' => $e->getMessage(),
            ], StatusCode::HTTP_BAD_REQUEST);
        } catch (ValidationException $e) {
            return JsonResponse::create([
                'errors' => $e->messages(),
            ], StatusCode::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $response;
    }
}
