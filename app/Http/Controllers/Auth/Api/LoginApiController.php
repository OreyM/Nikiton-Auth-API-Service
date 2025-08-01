<?php
/**
 * @author Serhii Makarov <oreymgt@gmail.com>
 * @git https://github.com/OreyM
 */

namespace App\Http\Controllers\Auth\Api;

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

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
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

        $tokenData = $authUser->createToken('NIKITON token');
        $token = $tokenData->accessToken;
        $tokenExpire = $tokenData->token->expires_at;

        return response()->json([
            'success'           => true,
            'code'              => Response::HTTP_OK,
            'message'           => 'Authentication success.',
            'token_type'        => 'Bearer',
            'token'             => $token,
            'token_expires_at'  => Carbon::parse($tokenExpire)->toDateTimeString()
        ]);
    }
}
