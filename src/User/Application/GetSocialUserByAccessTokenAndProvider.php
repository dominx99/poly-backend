<?php declare(strict_types = 1);

namespace App\User\Application;

use App\System\Contracts\Command;

class GetSocialUserByAccessTokenAndProvider implements Command
{
    /**
     * @var string
     */
    private $accessToken;

    /**
     * @var string
     */
    private $provider;

    /**
     * @param string $accessToken
     */
    public function __construct(string $accessToken, string $provider)
    {
        $this->accessToken = $accessToken;
        $this->provider    = $provider;
    }

    /**
     * @return string
     */
    public function accessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @return string
     */
    public function provider(): string
    {
        return $this->provider;
    }
}
