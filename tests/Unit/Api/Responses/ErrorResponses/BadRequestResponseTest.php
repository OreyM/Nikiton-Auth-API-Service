<?php
/**
 * @author Serhii Makarov <oreymgt@gmail.com>
 * @git https://github.com/OreyM
 */

namespace Tests\Unit\Api\Responses\ErrorResponses;

use App\Api\Responses\ErrorResponses\BadRequestResponse;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class BadRequestResponseTest extends TestCase
{
    public function test_respond()
    {
        $message = 'Bad Request';

        $response = (new BadRequestResponse($message))->respond();
        $data = json_decode($response->getContent(), true);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertFalse($data['success']);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $data['code']);
        $this->assertEquals($message, $data['message']);
    }
}
