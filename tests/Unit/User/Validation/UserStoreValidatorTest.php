<?php declare(strict_types = 1);

namespace Tests\Unit\User\Validation;

use App\System\Application\Exceptions\ValidationException;
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
    }

    /**
     * @test
     * @dataProvider wrongValues
     */
    public function that_validates_not_valid_values($params)
    {
        $validator = new UserStoreValidator($this->container->get(UserQueryRepository::class));

        $this->expectException(ValidationException::class);
        $validator->validate($params);
    }

    /**
     * @return array
     */
    public function wrongValues(): array
    {
        return [
            [['email' => 'example@test', 'abcdef']],
            [['email' => '', 'password' => 'abcdef']],
            [['email' => 'example@test.com', 'password' => 'abcd']],
            [[]],
        ];
    }
}
