<?php declare(strict_types=1);

namespace App\System\Infrastructure\Middleware;

use App\System\Infrastructure\StatusCode;
use App\System\Responses\JsonResponse;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\System\Infrastructure\Exceptions\BusinessException;

final class ExceptionMiddleware
{
    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(RequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $response = $handler->handle($request);
        } catch (BusinessException $e) {
            return JsonResponse::create([
                'error' => $e->getMessage(),
            ], StatusCode::HTTP_BAD_REQUEST);
        }

        return $response;
    }
}
