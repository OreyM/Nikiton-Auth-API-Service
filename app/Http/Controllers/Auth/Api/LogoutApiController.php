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

use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;
use OpenApi\Attributes\Tag;

#[Tag(name: 'Auth', description: 'Authentication API routes')]
final class LogoutApiController extends ApiController
{
    #[Post(
        path: '/api/v1/auth/logout',
        operationId: 'logoutUser',
        description: 'Revokes the current access token and logs the user out.',
        summary: 'Logout the authenticated user',
        tags: ['Authentication'],
        parameters: [
            new Parameter(
                name: 'Authorization',
                description: 'Token',
                in: 'header',
                required: true,
                schema: new Schema(
                    type: 'string',
                    example: 'Bearer {TOKEN_VALUE}'
                )
            ),
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Successfully logged out',
                content: new JsonContent(
                    properties: [
                        new Property(property: 'success', type: 'boolean', example: true),
                        new Property(property: 'code', type: 'integer', example: 200),
                        new Property(property: 'message', type: 'string', example: 'Log out successfully.')
                    ],
                    type: 'object'
                )
            ),
            new Response(
                response: 400,
                description: 'Logout error',
                content: new JsonContent(
                    properties: [
                        new Property(property: 'success', type: 'boolean', example: false),
                        new Property(property: 'code', type: 'integer', example: 400),
                        new Property(property: 'message', type: 'string', example: 'Log out error.')
                    ],
                    type: 'object'
                )
            )
        ]
    )]

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
