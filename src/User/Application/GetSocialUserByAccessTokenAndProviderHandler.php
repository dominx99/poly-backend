<?php declare(strict_types = 1);

namespace App\User\Application;

use Overtrue\Socialite\AccessToken;
use Overtrue\Socialite\SocialiteManager;

class GetSocialUserByAccessTokenAndProviderHandler
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
     * @param \App\User\Application\GetSocialUserByAccessTokenAndProvider $command
     * @return \Overtrue\Socialite\User|null
     */
    public function execute(GetSocialUserByAccessTokenAndProvider $command)
    {
        $accessToken = new AccessToken(['access_token' => $command->accessToken()]);

        $user = $this->socialite->driver($command->provider())->user($accessToken);

        if (! $user->getEmail()) {
            return null;
        }

        return $user;
    }
}
