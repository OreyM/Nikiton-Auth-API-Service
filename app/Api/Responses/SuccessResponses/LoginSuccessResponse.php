<?php
/**
 * @author Serhii Makarov <oreymgt@gmail.com>
 * @git https://github.com/OreyM
 */

namespace App\Api\Responses\SuccessResponses;

use App\Api\Responses\ApiResponseInterface;
use App\Domain\Auth\Entity\TokenEntity;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class LoginSuccessResponse implements ApiResponseInterface
{
    private TokenEntity $token;
    private string $message;

    /**
     * @param \App\Domain\Auth\Entity\TokenEntity $token
     * @param string $message
     */
    public function __construct(TokenEntity $token, string $message = 'Authentication success.')
    {
        $this->token = $token;
        $this->message = $message;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function respond(): JsonResponse
    {
        return response()->json([
            'success'           => true,
            'code'              => Response::HTTP_OK,
            'message'           => $this->message,
            'token'             => $this->token,
        ], Response::HTTP_OK);
    }
}
