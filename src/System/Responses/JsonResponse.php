<?php declare(strict_types = 1);

namespace App\System\Responses;

use Slim\Psr7\Response;

class JsonResponse extends Response
{
    /**
     * @param array $data
     * @param int $status
     */
    private function __construct($data = [], $status = 200)
    {
        parent::__construct($status);

        $this->getBody()->write(json_encode($data));
    }

    /**
     * @param array $data
     * @param int $status
     * @return self
     */
    public function create($data = [], $status = 200): self
    {
        $response = new static($data, $status);
        $response = $response->withHeader('Content-Type', 'application/json');

        return $response;
    }
}
