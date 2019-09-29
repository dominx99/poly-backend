<?php declare(strict_types=1);

namespace App\Map\Application\Validation;

use App\System\Application\Validation\Validator;
use Respect\Validation\Validator as v;

final class PutMapObjectValidator extends Validator
{
    public function __construct()
    {
        $this->extendRules([
            'field_id' => v::notEmpty(),
            'map_id'   => v::notEmpty(),
            'unit_id'  => v::notEmpty(),
            'type'     => v::notEmpty(),
        ]);
    }
}
