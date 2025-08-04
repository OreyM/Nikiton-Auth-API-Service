<?php
/**
 * @author Serhii Makarov <oreymgt@gmail.com>
 * @git https://github.com/OreyM
 */

namespace Tests\Unit\Api\Responses\SuccessResponses;

use App\Api\Responses\SuccessResponses\SuccessResponse;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class SuccessResponseTest extends TestCase
{
    public function test_respond(): void
    {
        $message = 'SUCCESS';

        $response = (new SuccessResponse($message))->respond();
        $data = json_decode($response->getContent(), true);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertTrue($data['success']);
        $this->assertEquals(Response::HTTP_OK, $data['code']);
        $this->assertEquals($message, $data['message']);
    }
}
