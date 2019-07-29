<?php declare(strict_types = 1);

namespace App\World\Responses;

use App\System\Responses\Success;
use App\World\Application\Query\worldView;

class WorldSuccess extends Success
{
    /**
     * @param \App\World\Application\Query\worldView $world
     */
    public function __construct(WorldView $world)
    {
        parent::__construct([
            'world' => [
                'id'     => $world->id(),
                'status' => $world->status(),
            ],
        ]);
    }
}
