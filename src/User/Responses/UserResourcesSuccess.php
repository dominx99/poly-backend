<?php declare(strict_types = 1);

namespace App\User\Responses;

use App\System\Responses\Success;
use App\User\Application\Query\ResourcesView;

class UserResourcesSuccess extends Success
{
    /**
     * @param \App\User\Application\Query\ResourcesView $resources
     */
    public function __construct(ResourcesView $resources)
    {
        parent::__construct([
            'gold' => $resources->gold(),
        ]);
    }
}
