<?php declare(strict_types = 1);

namespace App\System\Responses;

use App\System\Contracts\Responsable;
use App\System\Infrastructure\StatusMessage;
use Psr\Http\Message\ResponseInterface;
use App\System\Infrastructure\StatusCode;

class Fail implements Responsable
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
     * @var string
     */
    protected $message;

    /**
     * @param array $data
     * @param int $status
     * @param string $message
     */
    public function __construct(
        array $data = [],
        int $status = StatusCode::HTTP_BAD_REQUEST,
        string $message = StatusMessage::FAIL
    ) {
        $this->data    = $data;
        $this->status  = $status;
        $this->message = $message;
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function toResponse(): ResponseInterface
    {
        return JsonResponse::create(array_merge($this->data, [
            'status' => $this->message,
        ]), $this->status);
    }
}
