<?php declare (strict_types = 1);

namespace Tests\Unit\System\Application\Validation;

use Respect\Validation\Validator as v;
use App\System\Application\Validation\Validator;

class FakeValidator extends Validator
{
    public function extendFakeRule(): void
    {
        $this->extendRule('fake_rule', v::notEmpty());
    }

    public function extendFakeRules(): void
    {
        $this->extendRules([
            'fake_rule_2' => v::notEmpty(),
            'fake_rule_3' => v::notEmpty(),
        ]);
    }
}
