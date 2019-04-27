<?php declare (strict_types = 1);

namespace Tests\Unit\System\Application\Validation;

use Tests\BaseTestCase;
use Tests\Unit\System\Application\Validation\FakeValidator;
use Wallet\System\Application\Exceptions\ValidationRuleAlreadyExistsException;

class ValidatorTest extends BaseTestCase
{
    /** @test */
    public function that_extending_rules_works()
    {
        $fakeValidator = new FakeValidator();

        $this->assertEmpty($fakeValidator->getRules());

        $fakeValidator->extendFakeRule();

        $this->assertArrayHasKey('fake_rule', $fakeValidator->getRules());

        $fakeValidator->extendFakeRules();

        $this->assertArrayHasKey('fake_rule_2', $fakeValidator->getRules());
        $this->assertArrayHasKey('fake_rule_3', $fakeValidator->getRules());
    }

    /**
     * @test
     * @throws \Wallet\System\Application\Exceptions\ValidationRuleAlreadyExistsException
     */
    public function that_you_cannot_extend_same_rule_twice()
    {
        $this->expectException(ValidationRuleAlreadyExistsException::class);

        $fakeValidator = new FakeValidator();

        $fakeValidator->extendFakeRule();
        $fakeValidator->extendFakeRule();
    }

    /** @test */
    public function that_validation_works()
    {
        $fakeValidator = new FakeValidator();

        $fakeValidator->extendFakeRule();

        $params = ['fake_rule' => 'fake'];

        $fakeValidator->validate($params);

        $this->assertTrue($fakeValidator->passed());
        $this->assertFalse($fakeValidator->failed());
        $this->assertCount(0, $fakeValidator->getErrors());

        $params = ['fake_rule' => ''];

        $fakeValidator->validate($params);

        $this->assertTrue($fakeValidator->failed());
        $this->assertFalse($fakeValidator->passed());
        $this->assertCount(1, $fakeValidator->getErrors());

        $params = [];

        $fakeValidator->validate($params);

        $this->assertTrue($fakeValidator->failed());
        $this->assertFalse($fakeValidator->passed());
        $this->assertCount(1, $fakeValidator->getErrors());
    }
}
