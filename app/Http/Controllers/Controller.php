<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OAT;

#[
    OAT\Info(version: "1.0.0", description: "Test task", title: "Test-task-api Documentation"),
    OAT\Server(url: '/api/v1', description: 'API protocol v2'),
    OAT\SecurityScheme( securityScheme: 'bearerAuth', type: "http", name: "Authorization", in: "header", scheme: "bearer"),
]
#[OAT\Response(
    response: 'ServerErrorResponse',
    description: 'SERVER_ERROR',
    content: new OAT\MediaType(
        mediaType: 'application/json',
        schema: new OAT\Schema(
            required: [
                'title',
                'details',
            ],
            properties: [
                new OAT\Property(
                    property: 'title',
                    description: 'Код ошибки',
                    type: 'string',
                ),
                new OAT\Property(
                    property: 'details',
                    description: 'Детали ошибки',
                    type: 'string',
                ),
            ],
            type: 'object'
        )
    ),
)]
#[OAT\Response(
    response: 'UnauthorizedResponse',
    description: 'UNAUTHORIZED',
    content: new OAT\MediaType(
        mediaType: 'application/json',
        schema: new OAT\Schema(
            required: [
                'title',
                'details',
            ],
            properties: [
                new OAT\Property(
                    property: 'title',
                    description: 'Код ошибки',
                    type: 'string',
                ),
                new OAT\Property(
                    property: 'details',
                    description: 'Детали ошибки',
                    type: 'string',
                ),
            ],
            type: 'object'
        )
    ),
)]
#[OAT\Response(
    response: 'UnprocessableEntityResponse',
    description: 'UNPROCESSABLE_ENTITY',
    content: new OAT\MediaType(
        mediaType: 'application/json',
        schema: new OAT\Schema(
            required: [
                'title',
                'details',
                'errors',
            ],
            properties: [
                new OAT\Property(
                    property: 'title',
                    description: 'Код ошибки',
                    type: 'string',
                ),
                new OAT\Property(
                    property: 'details',
                    description: 'Детали ошибки',
                    type: 'string',
                ),
                new OAT\Property(
                    property: 'errors',
                    type: 'object',
                    example: [
                        'parameter1' => [
                            'The parameter1 is required.',
                            'The parameter1 must be a number.'
                        ],
                        'parameter2' => [
                            'The parameter2 must be a number.'
                        ]
                    ],
                    additionalProperties: new OAT\AdditionalProperties(
                        type: 'array',
                        items: new OAT\Items(
                            type: 'string',
                        )
                    ),
                )
            ],
            type: 'object'
        )
    )
)]
abstract class Controller
{
    //
}
