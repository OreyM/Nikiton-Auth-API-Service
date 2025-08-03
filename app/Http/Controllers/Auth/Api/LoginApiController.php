<?php
/**
 * @author Serhii Makarov <oreymgt@gmail.com>
 * @git https://github.com/OreyM
 */

namespace App\Http\Controllers\Auth\Api;

use App\Api\Responses\SuccessResponses\LoginSuccessResponse;
use App\Domain\Auth\Service\AuthService;
use App\Domain\Auth\Service\BearerTokenService;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\Queries\GetUserByEmailQuery;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Auth\LoginApiRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class LoginApiController extends ApiController
{
    private AuthService $authService;
    private BearerTokenService $tokenService;
    private GetUserByEmailQuery $getUserByEmailQuery;

    public function __construct(
        AuthService $authService,
        BearerTokenService $tokenService,
        GetUserByEmailQuery $getUserByEmailQuery
    )
    {
        $this->authService = $authService;
        $this->tokenService = $tokenService;
        $this->getUserByEmailQuery = $getUserByEmailQuery;
    }

    /**
     * @param \App\Http\Requests\Auth\LoginApiRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(LoginApiRequest $request): JsonResponse
    {
        if (!$authUser = $this->getUserByEmailQuery->handle($request->email)) {
            return response()->json([
                'success'   => false,
                'code'      => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message'   => 'Authentication failed [2]: credentials do not match records.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (!$this->authService->comparePasswords(urldecode($request->password), $authUser->password)) {
            return response()->json([
                'success'   => false,
                'code'      => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message'   => 'Authentication failed [3]: credentials do not match records.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $token = $this->tokenService
                ->setAuthUser($authUser)
                ->setTokenName('Nikiton API token')
                ->generateToken();
        } catch (UserNotFoundException $e) {
            return response()->json([
                'success'   => false,
                'code'      => Response::HTTP_NOT_FOUND,
                'message'   => $e->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        }

        return (new LoginSuccessResponse(
            token: $token,
            message: 'Authentication success.'
        ))->respond();
    }
}
