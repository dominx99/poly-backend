<?php declare(strict_types=1);

namespace App\System\Responses;

use App\System\Responses\JsonResponse;
use Psr\Http\Message\ResponseInterface;

final class SuccessResponse extends JsonResponse
{
    public function respond(): ResponseInterface
    {
        return JsonResponse::create(['status' => 'success']);
    }
}
