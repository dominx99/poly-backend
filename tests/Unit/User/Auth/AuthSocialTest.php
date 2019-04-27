<?php declare (strict_types = 1);

namespace Tests\Unit\User\Auth;

use Overtrue\Socialite\User;
use Tests\BaseTestCase;
use Tests\DatabaseTrait;
use Wallet\System\Infrastructure\StatusMessage;
use Wallet\User\Application\Exception\NotFoundSocialUserException;
use Wallet\User\Application\FindUserByEmail;
use Wallet\User\Application\GetSocialUserByAccessTokenAndProvider;
use Wallet\User\Application\LoginSocial;
use Wallet\User\Application\RegisterSocial;

class AuthSocialTest extends BaseTestCase
{
    use DatabaseTrait;

    /**
     * @test
     * @throws \Wallet\User\Application\Exception\NotFoundSocialUserException
     */
    public function that_wrong_access_token_throws_exception()
    {
        $this->expectException(NotFoundSocialUserException::class);

        $this->system->execute(new GetSocialUserByAccessTokenAndProvider(random_bytes(8), 'google'));
    }

    /** @test */
    public function that_wrong_access_token_returns_good_status_message()
    {
        $response = $this->runApp('POST', '/api/auth/login/google', [
            'access_token' => random_bytes(8),
        ]);

        $this->assertEquals(401, $response->getStatusCode());

        $body = json_decode((string) $response->getBody(), true);

        $this->assertEquals(StatusMessage::LOGIN_SOCIAL_ERROR, $body['error']);
    }

    /** @test */
    public function that_social_auth_works()
    {
        $socialUser = $this->createMock(User::class);

        $socialUser
            ->method('getEmail')
            ->willReturn('example@test.com');

        $socialUser
            ->method('getProviderName')
            ->willReturn('google');

        $this->system->handle(new RegisterSocial($socialUser));

        $this->assertDatabaseHas('users', [
            'email' => 'example@test.com',
        ]);

        $user = $this->system->execute(new FindUserByEmail('example@test.com'));

        $this->assertDatabaseHas('social_providers', [
            'user_id' => $user->id(),
            'name'    => 'google',
        ]);

        $result   = $this->system->execute(new LoginSocial($socialUser));
        $response = $result->toResponse();

        $body = json_decode((string) $response->getBody(), true);

        $this->assertArrayHasKey('token', $body['data']);
    }
}
