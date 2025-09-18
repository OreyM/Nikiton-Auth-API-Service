<?php
/**
 * @author Serhii Makarov <oreymgt@gmail.com>
 * @git https://github.com/OreyM
 */

namespace App\Api\Responses\ErrorResponses;

use App\Api\Responses\ApiResponseInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class UnprocessableEntityResponse implements ApiResponseInterface
{
    private string $message;

    public function __construct(string $message = 'UNPROCESSABLE ENTITY')
    {
        $this->message = $message;
    }

    public function respond(): JsonResponse
    {
        return response()->json([
            'success'   => false,
            'code'      => Response::HTTP_UNPROCESSABLE_ENTITY,
            'message'   => $this->message,
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
