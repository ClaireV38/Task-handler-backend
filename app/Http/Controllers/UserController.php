<?php

namespace App\Http\Controllers;

use App\Http\Responses\UserResponse;
use App\Models\User;
use App\Support\Http\Resources\Json\JsonResponseFactory;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class UserController extends Controller
{
    public function __construct(
        private JsonResponseFactory $jsonResponse
    ) {
    }

    /**
     * @return \App\Support\Http\Resources\Json\JsonResponse
     */
    #[OA\Get(
        path: '/api/users',
        operationId: 'getUsers',
        summary: 'List all users',
        description: 'Returns all users',
        tags: ['Users'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of users retrieved successfully',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        type: 'object',
                        properties: [
                            new OA\Property(
                                property: 'data',
                                type: 'array',
                                items: new OA\Items(
                                    type: 'object',
                                    properties: [
                                        new OA\Property(property: 'id', type: 'integer', example: 1),
                                        new OA\Property(property: 'name', type: 'string', example: 'Etienne Martin'),
                                        new OA\Property(
                                            property: 'email',
                                            type: 'string',
                                            example: 'etiennemartin@gmail.com'
                                        ),
                                        new OA\Property(
                                            property: 'password',
                                            type: 'string',
                                            format: 'password',
                                            example: 'MonMotDePasse123!'
                                        ),
                                        new OA\Property(
                                            property: 'remember_token',
                                            type: 'string',
                                            example: 'aqwzxdfdesbhjf'
                                        ),
                                        new OA\Property(
                                            property: 'created_at',
                                            type: 'string',
                                            format: 'date-time',
                                            example: '2025-10-12T08:30:00Z'
                                        ),
                                        new OA\Property(
                                            property: 'updated_at',
                                            type: 'string',
                                            format: 'date-time',
                                            example: '2025-10-13T09:00:00Z'
                                        ),
                                    ]
                                )
                            ),
                        ]
                    )
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
        ]
    )]
    public function index(Request $request)
    {
        $users = User::all();

        return $this->jsonResponse
            ->collection($users, new UserResponse())
            ->create();
    }
}
