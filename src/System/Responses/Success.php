<?php declare(strict_types = 1);

namespace App\System\Responses;

use App\System\Contracts\Responsable;
use App\System\Infrastructure\StatusMessage;
use Psr\Http\Message\ResponseInterface;

class Success implements Responsable
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @var int
     */
    protected $status;

    /**
     * @param array $data
     * @param integer $status
     */
    public function __construct(array $data = [], int $status = 200)
    {
        $this->data   = $data;
        $this->status = $status;
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function toResponse(): ResponseInterface
    {
        return JsonResponse::create([
            'data'   => $this->data,
            'status' => StatusMessage::SUCCESS,
        ], $this->status);
    }
}
