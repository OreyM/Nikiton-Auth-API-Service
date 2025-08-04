<?php
/**
 * @author Serhii Makarov <oreymgt@gmail.com>
 * @git https://github.com/OreyM
 */

namespace Tests\Unit\Api\Responses\ErrorResponses;

use App\Api\Responses\ErrorResponses\FailedValidationResponse;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class FailedValidationResponseTest extends TestCase
{
    public function test_responde(): void
    {
        $message = 'Validation failed.';
        $errors = [
            'email' => [
                'Email is invalid.',
            ],
            'password' => [
                'Password is invalid.',
            ],
        ];

        $response = (new FailedValidationResponse($errors, $message))->respond();
        $data = json_decode($response->getContent(), true);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
        $this->assertFalse($data['success']);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $data['code']);
        $this->assertEquals($message, $data['message']);
        $this->assertArrayHasKey('email', $data['errors']);
        $this->assertArrayHasKey('password', $data['errors']);
        $this->assertEquals($errors['email'][0], $data['errors']['email'][0]);
        $this->assertEquals($errors['password'][0], $data['errors']['password'][0]);
    }
}
