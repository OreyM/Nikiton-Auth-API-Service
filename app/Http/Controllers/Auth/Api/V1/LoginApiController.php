<?php
/**
 * @author Serhii Makarov <oreymgt@gmail.com>
 * @git https://github.com/OreyM
 */

namespace App\Http\Controllers\Auth\Api\V1;

use App\Actions\Action;
use App\Api\Responses\SuccessResponses\LoginSuccessResponse;
use App\Domain\Auth\Actions\LoginUserAction;
use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Tag;

#[Tag(name: 'Auth', description: 'Authentication API routes')]
final class LoginApiController extends ApiController
{
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        $token = Action::call(LoginUserAction::class)->run();

        return (new LoginSuccessResponse(
            token: $token,
            message: trans('auth.success')
        ))->respond();
    }
}
