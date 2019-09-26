<?php declare(strict_types=1);

namespace App\System\Responses;

use Psr\Http\Message\ResponseInterface;

final class SuccessResponse extends JsonResponse
{
    public static function respond(): ResponseInterface
    {
        return JsonResponse::create(['status' => 'success']);
    }
}
