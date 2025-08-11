<?php

namespace Tests\Unit\Api\Responses\ErrorResponses;

use App\Api\Responses\ErrorResponses\UnauthorizedResponse;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class UnauthorizedResponseTest extends TestCase
{
    public function test_respond(): void
    {
        $message = 'UNAUTHORIZED';

        $response = (new UnauthorizedResponse($message))->respond();
        $data = json_decode($response->getContent(), true);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
        $this->assertFalse($data['success']);
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $data['code']);
        $this->assertEquals($message, $data['message']);
    }
}
