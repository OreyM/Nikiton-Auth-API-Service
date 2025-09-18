<?php
/**
 * @author Serhii Makarov <oreymgt@gmail.com>
 * @git https://github.com/OreyM
 */

namespace Tests\Unit\Api\Responses\ErrorResponses;

use App\Api\Responses\ErrorResponses\UnprocessableEntityResponse;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class UnprocessableEntityResponseTest extends TestCase
{
    public function test_respond(): void
    {
        $message = 'UNPROCESSABLE ENTITY';

        $response = (new UnprocessableEntityResponse($message))->respond();
        $data = json_decode($response->getContent(), true);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
        $this->assertFalse($data['success']);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $data['code']);
        $this->assertEquals($message, $data['message']);
    }
}
