<?php declare(strict_types = 1);

namespace App\System\Responses;

use App\System\Contracts\Responsable;
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
     * @param array $data
     * @param integer $status
     * @return self
     */
    public static function create(array $data = [], int $status = 200): self
    {
        return new static($data, $status);
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function toResponse(): ResponseInterface
    {
        return SuccessResponse::respond($this->data);
    }
}
