<?php declare(strict_types = 1);

namespace App\System\Contracts;

use Psr\Http\Message\ResponseInterface;

interface Responsable
{
    /**
     * @return ResponseInterfacevoid
     */
    public function toResponse(): ResponseInterface;
}
