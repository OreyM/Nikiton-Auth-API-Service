<?php

namespace App\Domain\Auth\Actions;

use App\Actions\Action;
use Illuminate\Http\Request;
use Laravel\Passport\AccessToken;

final class LogoutAuthUserAction extends Action
{
    private Request $request;

    public function __construct(Request $request)
    {

        $this->request = $request;
    }

    protected function handle(): bool
    {
        /** @var AccessToken  $token */
        $token = $this->request->user()->token();

        return $token->revoke();
    }
}
