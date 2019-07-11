<?php declare(strict_types = 1);

namespace App\System\Responses;

use Slim\Http\Response;
use Slim\Http\StatusCode;
use App\System\Contracts\Responsable;
use App\System\Infrastructure\StatusMessage;

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
     * @param array $data
     * @param integer $status
     */
    public function __construct(
        array $data = [],
        int $status = StatusCode::HTTP_BAD_REQUEST,
        string $message = StatusMessage::FAIL
    ) {
        $this->data   = $data;
        $this->status = $status;
        $this->setFailMessage($message);
    }

    /**
     * @return \Slim\Http\Response
     */
    public function toResponse(): Response
    {
        return (new Response())->withJson($this->data, $this->status);
    }

    /**
     * @return void
     */
    public function setFailMessage(string $message): void
    {
        $this->data['status'] = $message;
    }
}
