<?php

namespace App\Api\Documantation\Schemas;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    title: 'Auth token',
    description: 'Token response after successful login',
    properties: [
        new Property(property: 'type', type: 'string', example: 'Bearer'),
        new Property(property: 'value', type: 'string', example: 'TOKEN_VALUE'),
        new Property(property: 'expiresAt', type: 'string', format: 'date-time', example: '2025-07-07T23:59:59Z'),
    ],
    type: 'object'
)]
final class TokenSchema
{

}
