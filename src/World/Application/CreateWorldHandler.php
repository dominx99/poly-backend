<?php declare(strict_types = 1);

namespace App\World\Application;

use App\World\Contracts\WorldsWriteRepository;

class CreateWorldHandler
{
    /**
     * @var \App\World\Contracts\WorldsWriteRepository
     */
    private $worlds;

    /**
     * @param \App\World\Contracts\WorldsWriteRepository $worlds
     */
    public function __construct(WorldsWriteRepository $worlds)
    {
        $this->worlds = $worlds;
    }

    /**
     * @param \App\World\Application\CreateWorld $command
     * @return void
     */
    public function handle(CreateWorld $command): void
    {
        $this->worlds->add([
            'id'     => $command->id(),
            'status' => $command->status(),
        ]);
    }
}
