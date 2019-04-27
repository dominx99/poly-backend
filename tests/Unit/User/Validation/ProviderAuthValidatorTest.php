<?php declare (strict_types = 1);

namespace Tests\Unit\User\Validation;

use Tests\BaseTestCase;
use Wallet\User\Application\Validation\ProviderAuthValidator;

class ProviderAuthValidatorTest extends BaseTestCase
{
    /** @test */
    public function that_valid_params_passes()
    {
        $params = ['access_token' => random_bytes(8)];

        $providerValidator = new ProviderAuthValidator();

        $providerValidator->validate($params);

        $this->assertTrue($providerValidator->passed());
        $this->assertFalse($providerValidator->failed());

        $params = ['access_token' => null];

        $providerValidator->validate($params);

        $this->assertTrue($providerValidator->failed());
        $this->assertFalse($providerValidator->passed());

        $params = [];

        $providerValidator->validate($params);

        $this->assertTrue($providerValidator->failed());
        $this->assertFalse($providerValidator->passed());
    }
}
