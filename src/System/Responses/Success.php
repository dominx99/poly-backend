<?php declare (strict_types = 1);

namespace App\System\Responses;

use Slim\Http\Response;
use App\System\Contracts\Responsable;
use App\System\Infrastructure\StatusMessage;

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
        $this->setSuccessMessage();
    }

    /**
     * @return \Slim\Http\Response
     */
    public function toResponse(): Response
    {
        return (new Response())->withJson(['data' => $this->data], $this->status);
    }

    /**
     * @return void
     */
    public function setSuccessMessage(): void
    {
        $this->data['status'] = StatusMessage::SUCCESS;
    }
}
