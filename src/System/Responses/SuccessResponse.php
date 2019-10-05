<?php declare(strict_types=1);

namespace App\System\Responses;

use Psr\Http\Message\ResponseInterface;

final class SuccessResponse extends JsonResponse
{
    /**
     * @param array $data
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function respond(array $data = []): ResponseInterface
    {
        return JsonResponse::create(array_merge(
            ['status' => 'success'],
            ['data'   => $data]
        ));
    }
}
