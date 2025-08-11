<?php

namespace App\Api\Responses\ErrorResponses;

use App\Api\Responses\ApiResponseInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UnauthorizedResponse implements ApiResponseInterface
{
    private string $message;

    public function __construct(string $message = 'Unauthorized.')
    {
        $this->message = $message;
    }

    public function respond(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'code'    => Response::HTTP_UNAUTHORIZED,
            'message' => $this->message,
        ], Response::HTTP_UNAUTHORIZED);
    }
}
