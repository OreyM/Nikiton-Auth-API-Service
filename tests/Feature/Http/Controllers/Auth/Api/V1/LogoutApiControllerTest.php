<?php

namespace Tests\Feature\Http\Controllers\Auth\Api\V1;

use App\Models\User;
use Laravel\Passport\Contracts\ScopeAuthorizable;
use Laravel\Passport\Passport;
use Laravel\Passport\Token;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\FeatureTestCase;

final class LogoutApiControllerTest extends FeatureTestCase
{
    private string $logoutApiUri = '/api/v1/auth/logout';

    #[Test]
    public function logout_auth_user_success(): void
    {
        $user = User::where('email', config('auth.user.default.login'))->first();
        $tokenResult = $user->createToken('Test Token');
        $token = $tokenResult->accessToken;

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token,])
            ->postJson($this->logoutApiUri);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'success' => true,
                'code'    => Response::HTTP_OK,
                'message' => trans('auth.logout'),
            ]);

    }

    #[Test]
    public function logout_revoke_fails_returns_bad_request(): void
    {
        $user = User::where('email', config('auth.user.default.login'))
            ->first();

        $mockToken = Mockery::mock(Token::class, ScopeAuthorizable::class);
        $mockToken->shouldReceive('revoke')->once()->andReturn(false);

        $mockUser = Mockery::mock($user)->makePartial();
        $mockUser->shouldReceive('token')
            ->once()
            ->andReturn($mockToken);
        $mockUser->shouldReceive('withAccessToken')
            ->andReturnSelf();

        Passport::actingAs($mockUser);

        $response = $this->postJson($this->logoutApiUri);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJson([
                'success'   => false,
                'code'      => Response::HTTP_BAD_REQUEST,
                'message'   => trans('auth.logout_error'),
            ]);
    }


}
