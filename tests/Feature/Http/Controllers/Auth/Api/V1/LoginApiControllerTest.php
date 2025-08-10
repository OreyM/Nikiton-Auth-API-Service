<?php

namespace Tests\Feature\Http\Controllers\Auth\Api\V1;

use App\Data\Types\TokenType;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\FeatureTestCase;

final class LoginApiControllerTest extends FeatureTestCase
{
    private string $loginApiUri = '/api/v1/auth/login';

    #[Test]
    public function login_success(): void
    {
        $response = $this->postJson($this->loginApiUri, [
            'email'     => 'user_test@mail.com',
            'password'  => 'test_password',
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'success'   => true,
                'code'      => Response::HTTP_OK,
                'message'   => trans('auth.success'),
            ])
            ->assertJsonStructure([
                'token' => [
                    'type',
                    'value',
                    'expiresAt',
                ],
            ]);
    }

    #[Test]
    public function correct_token_structure(): void
    {
        $response = $this->postJson($this->loginApiUri, [
            'email'     => 'user_test@mail.com',
            'password'  => 'test_password',
        ]);
        $token = $response->json('token');

        $this->assertEquals(TokenType::BEARER, $token['type']);
        $this->assertNotEmpty($token['value']);
        $this->assertIsString($token['value']);
        $this->assertNotEmpty($token['expiresAt']);
        $this->assertInstanceOf(Carbon::class, Carbon::parse($token['expiresAt']));
    }

    #[Test]
    public function auth_user_not_found(): void
    {
        $response = $this->postJson($this->loginApiUri, [
            'email'     => 'no_user@mail.com',
            'password'  => 'test_password',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'success'   => false,
                'code'      => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message'   => trans('auth.failed'),
            ]);
    }

    #[Test]
    public function login_password_does_not_match(): void
    {
        $response = $this->postJson($this->loginApiUri, [
            'email'     => 'user_test@mail.com',
            'password'  => 'wrong_password',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'success'   => false,
                'code'      => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message'   => trans('auth.failed'),
            ]);
    }
}
