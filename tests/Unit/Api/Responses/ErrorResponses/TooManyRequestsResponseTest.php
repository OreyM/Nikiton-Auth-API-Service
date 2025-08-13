<?php

namespace Tests\Unit\Api\Responses\ErrorResponses;

use App\Api\Responses\ErrorResponses\TooManyRequestsResponse;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class TooManyRequestsResponseTest extends TestCase
{
    public function test_respond(): void
    {
        $message = 'TOO MANY REQUEST.';

        $response = (new TooManyRequestsResponse($message))->respond();
        $data = json_decode($response->getContent(), true);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_TOO_MANY_REQUESTS, $response->getStatusCode());
        $this->assertFalse($data['success']);
        $this->assertEquals(Response::HTTP_TOO_MANY_REQUESTS, $data['code']);
        $this->assertEquals($message, $data['message']);
    }
}
