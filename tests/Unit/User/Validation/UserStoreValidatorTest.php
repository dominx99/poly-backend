<?php declare(strict_types = 1);

namespace Tests\Unit\User\Validation;

use Tests\BaseTestCase;
use App\User\Application\Validation\UserStoreValidator;
use App\User\Contracts\UserQueryRepository;
use Tests\DatabaseTrait;

class UserStoreValidatorTest extends BaseTestCase
{
    use DatabaseTrait;

    /** @test */
    public function that_it_validates_properly()
    {
        $validator = new UserStoreValidator($this->container->get(UserQueryRepository::class));

        $params = ['email' => 'example@test.com', 'password' => 'abcdef'];
        $validator->validate($params);
        $this->assertTrue($validator->passed());

        $params = ['email' => 'example@test', 'abcdef'];
        $validator->validate($params);
        $this->assertTrue($validator->failed());

        $params = ['email' => '', 'password' => 'abcdef'];
        $validator->validate($params);
        $this->assertTrue($validator->failed());

        $params = ['email' => 'example@test.com', 'password' => 'abcd'];
        $validator->validate($params);
        $this->assertTrue($validator->failed());

        $params = [];
        $validator->validate($params);
        $this->assertTrue($validator->failed());
    }
}
