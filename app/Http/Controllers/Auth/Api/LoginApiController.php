<?php
/**
 * @author Serhii Makarov <oreymgt@gmail.com>
 * @git https://github.com/OreyM
 */

namespace App\Http\Controllers\Auth\Api;

use App\Domain\Auth\Service\AuthService;
use App\Domain\Auth\Service\BearerTokenService;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\Queries\GetUserByEmailQuery;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginApiRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class LoginApiController extends Controller
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
            ]);
        }

        if (!$this->authService->comparePasswords(urldecode($request->password), $authUser->password)) {
            return response()->json([
                'success'   => false,
                'code'      => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message'   => 'Authentication failed [3]: credentials do not match records.'
            ]);
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
            ]);
        }

        return response()->json([
            'success'           => true,
            'code'              => Response::HTTP_OK,
            'message'           => 'Authentication success.',
            'token'             => $token,
        ]);
    }
}
