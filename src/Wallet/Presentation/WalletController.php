<?php declare (strict_types = 1);

namespace Wallet\Wallet\Presentation;

use Slim\Http\Request;
use Slim\Http\Response;
use Wallet\System\Responses\Success;
use Wallet\System\Responses\ValidationFail;
use Wallet\System\System;
use Wallet\Wallet\Application\CreateWallet;
use Wallet\Wallet\Application\Validation\WalletStoreValidator;

class WalletController
{
    /**
     * @var \Wallet\System\System
     */
    private $system;

    /**
     * @var \Wallet\Wallet\Application\Validation\WalletStoreValidator
     */
    private $validator;

    /**
     * @param \Wallet\System\System $system
     */
    public function __construct(System $system, WalletStoreValidator $validator)
    {
        $this->system    = $system;
        $this->validator = $validator;
    }

    /**
     * @param \Slim\Http\Request $request
     * @return \Slim\Http\Response
     */
    public function store(Request $request): Response
    {
        $validation = $this->validator->validate($request->getParams());

        if ($validation->failed()) {
            return (new ValidationFail($validation->getErrors()))->toResponse();
        }

        $ownerId = $request->getAttribute('decodedToken')['id'];

        $this->system->handle(
            new CreateWallet($ownerId, $request->getParam('name'))
        );

        return (new Success())->toResponse();
    }
}
