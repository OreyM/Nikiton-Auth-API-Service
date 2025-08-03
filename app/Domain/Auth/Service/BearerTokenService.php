<?php
/**
 * @author Serhii Makarov <oreymgt@gmail.com>
 * @git https://github.com/OreyM
 */

namespace App\Domain\Auth\Service;

use App\Data\Types\TokenType;
use App\Domain\Auth\Entity\TokenEntity;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Models\User;
use Carbon\Carbon;

class BearerTokenService
{
    private ?User $authUser;
    private string $tokenName;

    public function __construct()
    {
        $this->authUser = null;
        $this->tokenName = 'API';
    }

    public function setAuthUser(User $authUser): self
    {
        $this->authUser = $authUser;

        return $this;
    }

    public function getAuthUser(): User
    {
        return $this->authUser;
    }

    public function setTokenName(string $name): self
    {
        $this->tokenName = $name;

        return $this;
    }

    public function getTokenName(): string
    {
        return $this->tokenName;
    }


    /**
     * @return \App\Domain\Auth\Entity\TokenEntity
     *
     * @throws UserNotFoundException
     */
    public function generateToken(): TokenEntity
    {
        if (!$this->authUser) {
            throw new UserNotFoundException();
        }

        $token = $this->authUser->createToken($this->tokenName);

        return new TokenEntity(
            type: TokenType::BEARER,
            value: $token->accessToken,
            expiresAt: Carbon::parse($token->token->expires_at)
        );
    }
}
