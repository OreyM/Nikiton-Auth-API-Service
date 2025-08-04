<?php
/**
 * @author Serhii Makarov <oreymgt@gmail.com>
 * @git https://github.com/OreyM
 */

namespace Api\Responses\ErrorResponses;

use App\Api\Responses\ErrorResponses\NotFoundResponse;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class NotFoundResponseTest extends TestCase
{
    public function test_respond(): void
    {
        $message = 'Not Found';

        $response = (new NotFoundResponse($message))->respond();
        $data = json_decode($response->getContent(), true);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        $this->assertFalse($data['success']);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $data['code']);
        $this->assertEquals($message, $data['message']);
    }
}
