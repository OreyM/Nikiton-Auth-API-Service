<?php
/**
 * @author Serhii Makarov <oreymgt@gmail.com>
 * @git https://github.com/OreyM
 */

namespace App\Http\Controllers\Auth\Api;

use App\Api\Responses\ErrorResponses\BadRequestResponse;
use App\Api\Responses\SuccessResponses\SuccessResponse;
use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Passport\AccessToken;

final class LogoutApiController extends ApiController
{
    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        /** @var AccessToken  $token */
        $token = $request->user()->token();

        if (! $token->revoke()) {
            return (new BadRequestResponse(
                message: trans('auth.logout_error')
            ))->respond();
        }

        return (new SuccessResponse(
            message: trans('auth.logout')
        ))->respond();
    }
}
