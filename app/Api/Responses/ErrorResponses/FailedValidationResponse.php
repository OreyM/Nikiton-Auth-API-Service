<?php
/**
 * @author Serhii Makarov <oreymgt@gmail.com>
 * @git https://github.com/OreyM
 */

namespace App\Api\Responses\ErrorResponses;

use App\Api\Responses\ApiResponseInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class FailedValidationResponse implements ApiResponseInterface
{
    private string $message;
    private array $errors;

    public function __construct(array $errors, string $message = 'VALIDATION FAILED')
    {
        $this->message = $message;
        $this->errors = $errors;
    }

    public function respond(): JsonResponse
    {
        return response()->json([
            'success'   => false,
            'code'      => Response::HTTP_UNPROCESSABLE_ENTITY,
            'message'   => $this->message,
            'errors'    => $this->errors,
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
