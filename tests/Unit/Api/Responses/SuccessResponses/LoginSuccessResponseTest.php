<?php
/**
 * @author Serhii Makarov <oreymgt@gmail.com>
 * @git https://github.com/OreyM
 */

namespace Tests\Unit\Api\Responses\SuccessResponses;

use App\Api\Responses\SuccessResponses\LoginSuccessResponse;
use App\Data\Types\TokenType;
use App\Domain\Auth\Entity\TokenEntity;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class LoginSuccessResponseTest extends TestCase
{
    public function test_respond(): void
    {
        $message = 'LOGIN SUCCESSFUL';
        $token = new TokenEntity(
            type: TokenType::BEARER,
            value: 'test-token-value',
            expiresAt: Carbon::now()->addHour()
        );

        $response = (new LoginSuccessResponse($token, $message))->respond();
        $data = json_decode($response->getContent(), true);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertEquals(Response::HTTP_OK, $data['code']);
        $this->assertEquals($message, $data['message']);
        $this->assertArrayHasKey('token', $data);
        $this->assertIsArray($data['token']);
        $this->assertEquals(TokenType::BEARER, $data['token']['type']);
        $this->assertEquals('test-token-value', $data['token']['value']);
        $this->assertEquals(
            $token->expiresAt->toISOString(),
            Carbon::parse($data['token']['expiresAt'])->toISOString()
        );
    }
}
