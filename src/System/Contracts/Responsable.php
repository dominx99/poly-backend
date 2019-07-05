<?php declare(strict_types = 1);

namespace App\System\Contracts;

use Slim\Http\Response;

interface Responsable
{
    /**
     * @return \Slim\Http\Response
     */
    public function toResponse(): Response;
}
