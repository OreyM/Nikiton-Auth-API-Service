<?php
/**
 * @author Serhii Makarov <oreymgt@gmail.com>
 * @git https://github.com/OreyM
 */

namespace App\Http\Controllers\Auth\Api;

use App\Api\Responses\ErrorResponses\NotFoundResponse;
use App\Api\Responses\ErrorResponses\UnprocessableEntityResponse;
use App\Api\Responses\SuccessResponses\LoginSuccessResponse;
use App\Domain\Auth\Service\AuthService;
use App\Domain\Auth\Service\BearerTokenService;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\Queries\GetUserByEmailQuery;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Auth\LoginApiRequest;
use Illuminate\Http\JsonResponse;

use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Tag;

#[Tag(name: 'Auth', description: 'Authentication API routes')]
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

    #[Post(
        path: '/api/v1/auth/login',
        operationId: 'loginUser',
        description: 'Authenticates the user and returns a Bearer token.',
        summary: 'User login',
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(
                required: ['email', 'password'],
                properties: [
                    new Property(property: 'email', type: 'string', format: 'email', example: 'user@mail.com'),
                    new Property(property: 'password', type: 'string', format: 'password', example: 'PASSWORD')
                ]
            )
        ),
        tags: ['Authentication'],
        responses: [
            new Response(
                response: 200,
                description: 'Successful login',
                content: new JsonContent(
                    properties: [
                        new Property(property: 'success', type: 'boolean', example: true),
                        new Property(property: 'code', type: 'integer', example: 200),
                        new Property(property: 'message', type: 'string', example: 'Authentication success.'),
                        new Property(property: 'token', ref: '#/components/schemas/TokenSchema')
                    ]
                )
            ),
            new Response(
                response: 404,
                description: 'User not found',
                content: new JsonContent(
                    properties: [
                        new Property(property: 'success', type: 'boolean', example: false),
                        new Property(property: 'code', type: 'integer', example: 404),
                        new Property(property: 'message', type: 'string', example: 'User was not found.')
                    ]
                )
            ),
            new Response(
                response: 422,
                description: 'Unprocessable Entity (invalid credentials)',
                content: new JsonContent(
                    properties: [
                        new Property(property: 'success', type: 'boolean', example: false),
                        new Property(property: 'code', type: 'integer', example: 422),
                        new Property(property: 'message', type: 'string', example: 'These credentials do not match our records.')
                    ]
                )
            ),
        ]
    )]

    /**
     * @param \App\Http\Requests\Auth\LoginApiRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(LoginApiRequest $request): JsonResponse
    {
        if (!$authUser = $this->getUserByEmailQuery->handle($request->email)) {
            return (new UnprocessableEntityResponse(
                message: trans('auth.failed')
            ))->respond();
        }

        if (!$this->authService->comparePasswords(urldecode($request->password), $authUser->password)) {
            return (new UnprocessableEntityResponse(
                message: trans('auth.failed')
            ))->respond();
        }

        try {
            $token = $this->tokenService
                ->setAuthUser($authUser)
                ->setTokenName('Nikiton API token') // TODO remove to .env or other place
                ->generateToken();
        } catch (UserNotFoundException $e) {
            return (new NotFoundResponse(
                message: $e->getMessage()
            ))->respond();
        }

        return (new LoginSuccessResponse(
            token: $token,
            message: trans('auth.success')
        ))->respond();
    }
}
