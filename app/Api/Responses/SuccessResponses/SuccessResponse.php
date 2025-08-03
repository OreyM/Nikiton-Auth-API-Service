<?php
/**
 * @author Serhii Makarov <oreymgt@gmail.com>
 * @git https://github.com/OreyM
 */

namespace App\Api\Responses\SuccessResponses;

use App\Api\Responses\ApiResponseInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SuccessResponse implements ApiResponseInterface
{
    private string $message;

    public function __construct(string $message = 'Success.')
    {
        $this->message = $message;
    }

    public function respond(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'code'    => Response::HTTP_OK,
            'message' => $this->message,
        ], Response::HTTP_OK);
    }
}
