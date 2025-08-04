<?php
/**
 * @author Serhii Makarov <oreymgt@gmail.com>
 * @git https://github.com/OreyM
 */

namespace Tests\Unit\Http\Requests;

use App\Api\Responses\ErrorResponses\FailedValidationResponse;
use App\Http\Requests\ApiRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\MessageBag;
use Mockery;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class ApiRequestTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_failed_validation_throws_http_response_exception(): void
    {
        $errors = ['email' => ['The email field is required.']];
        $responseErrorMessage = 'VALIDATION FAILED';
        $expectedResponse = (new FailedValidationResponse($errors, $responseErrorMessage))->respond();

        $mockValidator = Mockery::mock(Validator::class);
        $mockMessageBag = new MessageBag($errors);
        $mockValidator->shouldReceive('errors')
            ->andReturn($mockMessageBag);

        $request = Mockery::mock(ApiRequest::class)->makePartial();

        $this->expectException(HttpResponseException::class);

        try {
            $request->failedValidation($mockValidator);
        } catch (HttpResponseException $e) {
            $response = $e->getResponse();

            $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
            $this->assertJsonStringEqualsJsonString($expectedResponse->getContent(), $response->getContent());

            throw $e;
        }
    }
}
