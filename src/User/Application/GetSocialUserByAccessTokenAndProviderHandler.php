<?php declare (strict_types = 1);

namespace Wallet\User\Application;

use Overtrue\Socialite\AccessToken;
use Overtrue\Socialite\SocialiteManager;
use Overtrue\Socialite\User;
use Wallet\System\Contracts\Query;
use Wallet\User\Application\Exception\NotFoundSocialUserException;
use Wallet\User\Application\GetSocialUserByAccessTokenAndProvider;

class GetSocialUserByAccessTokenAndProviderHandler implements Query
{
    /**
     * @var \Overtrue\Socialite\SocialiteManager
     */
    protected $socialite;

    /**
     * @param \Overtrue\Socialite\SocialiteManager $socialite
     */
    public function __construct(SocialiteManager $socialite)
    {
        $this->socialite = $socialite;
    }

    /**
     * @param \Wallet\User\Application\GetSocialUserByAccessTokenAndProvider $command
     * @return \Overtrue\Socialite\User
     */
    public function execute(GetSocialUserByAccessTokenAndProvider $command): User
    {
        $accessToken = new AccessToken(['access_token' => $command->accessToken()]);

        $user = $this->socialite->driver($command->provider())->user($accessToken);

        if (!$user->getEmail()) {
            throw new NotFoundSocialUserException();
        }

        return $user;
    }
}
