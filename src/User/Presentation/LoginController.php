<?php declare(strict_types = 1);

namespace App\User\Presentation;

use Overtrue\Socialite\SocialiteManager;
use App\System\Responses\ValidationFail;
use App\System\System;
use App\User\Application\FindUserByEmail;
use App\User\Application\GetSocialUserByAccessTokenAndProvider;
use App\User\Application\LoginSocial;
use App\User\Application\LoginStandard;
use App\User\Application\RegisterSocial;
use App\User\Application\Validation\ProviderAuthValidator;
use App\User\Responses\LoginFail;
use App\System\Infrastructure\StatusMessage;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class LoginController
{
    /**
     * @var \App\System\System
     */
    protected $system;

    /**
     * @var \Overtrue\Socialite\SocialiteManager
     */
    protected $socialite;

    /**
     * @var \App\User\Application\Validation\ProviderAuthValidator
     */
    protected $providerValidator;

    /**
     * @param \App\System\System $system
     * @param \Overtrue\Socialite\SocialiteManager $socialite
     * @param \App\User\Application\Validation\ProviderAuthValidator $providerValidator
     */
    public function __construct(
        System $system,
        SocialiteManager $socialite,
        ProviderAuthValidator $providerValidator
    ) {
        $this->system            = $system;
        $this->socialite         = $socialite;
        $this->providerValidator = $providerValidator;
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function login(RequestInterface $request): ResponseInterface
    {
        $params = $request->getParams();

        $query = new LoginStandard($params['email'], $params['password']);

        $result = $this->system->execute($query);

        return $result->toResponse();
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @param string $provider
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function loginByProvider(
        RequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $validation = $this->providerValidator->validate($request->getParams() ?? []);

        if ($validation->failed()) {
            return (new ValidationFail($validation->getErrors()))->toResponse();
        }

        if (!$socialUser = $this->system->execute(
            new GetSocialUserByAccessTokenAndProvider($request->getParam('access_token'), $args['provider'])
        )) {
            return (new LoginFail(StatusMessage::LOGIN_SOCIAL_ERROR))->toResponse();
        }

        $user = $this->system->execute(new FindUserByEmail($socialUser->getEmail()));

        if (!$user) {
            $this->system->handle(new RegisterSocial($socialUser));
        }

        $result = $this->system->execute(new LoginSocial($socialUser));

        return $result->toResponse();
    }
}
