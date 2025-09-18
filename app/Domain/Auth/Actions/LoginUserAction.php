<?php

namespace App\Domain\Auth\Actions;

use App\Actions\Action;
use App\Domain\Auth\Entity\TokenEntity;
use App\Domain\Auth\Exceptions\AuthFailedException;
use App\Domain\Auth\Service\AuthService;
use App\Domain\Auth\Service\BearerTokenService;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\Queries\GetUserByEmailQuery;
use App\Http\Requests\Auth\LoginApiRequest;

final class LoginUserAction extends Action
{
    private AuthService $authService;
    private BearerTokenService $tokenService;
    private GetUserByEmailQuery $getUserByEmailQuery;
    private LoginApiRequest $request;

    public function __construct(
        AuthService $authService,
        BearerTokenService $tokenService,
        GetUserByEmailQuery $getUserByEmailQuery,
        LoginApiRequest $request
    )
    {
        $this->request = $request;
        $this->authService = $authService;
        $this->tokenService = $tokenService;
        $this->getUserByEmailQuery = $getUserByEmailQuery;
    }

    /**
     * @throws AuthFailedException
     * @throws UserNotFoundException
     */
    protected function handle(): TokenEntity
    {
        $authUser = $this->getUserByEmailQuery
            ->handle($this->request->email);

        if (! $authUser) {
            throw new AuthFailedException();
        }

        if (! $this->authService->comparePasswords(urldecode($this->request->password), $authUser->password)) {
            throw new AuthFailedException();
        }

        return $this->tokenService
            ->setAuthUser($authUser)
            ->setTokenName('Nikiton API token') // TODO remove to .env or other place
            ->generateToken();
    }
}
