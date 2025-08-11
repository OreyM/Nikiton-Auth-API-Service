<?php

namespace App\Api\Responses\ErrorResponses;

use App\Api\Responses\ApiResponseInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class TooManyRequestsResponse implements ApiResponseInterface
{
    private string $message;

    public function __construct(string $message = 'Too many requests.')
    {
        $this->message = $message;
    }

    public function respond(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'code'    => Response::HTTP_TOO_MANY_REQUESTS,
            'message' => $this->message,
        ], Response::HTTP_TOO_MANY_REQUESTS);
    }
}
