<?php
/**
 * @author Serhii Makarov <oreymgt@gmail.com>
 * @git https://github.com/OreyM
 */

namespace App\Http\Controllers\Auth\Api;

use App\Data\Types\TokenType;
use App\Domain\Auth\Entity\TokenEntity;
use App\Domain\Auth\Service\BearerTokenService;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginApiRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class LoginApiController extends Controller
{
    private User $userModel;
    private BearerTokenService $tokenService;

    public function __construct(User $userModel, BearerTokenService $tokenService)
    {
        $this->userModel = $userModel;
        $this->tokenService = $tokenService;
    }

    /**
     * @param \App\Http\Requests\Auth\LoginApiRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(LoginApiRequest $request): JsonResponse
    {
        $authUser = $this->userModel->where('email', $request->email)
            ->first();

        if (!$authUser) {
            return response()->json([
                'success' => false,
                'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message' => 'Authentication failed [2]: credentials do not match records.'
            ]);
        }

        if (!Hash::check(urldecode($request->password), $authUser->password)) {
            return response()->json([
                'success' => false,
                'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message' => 'Authentication failed [3]: credentials do not match records.'
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
