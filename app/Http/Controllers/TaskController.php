<?php

namespace App\Http\Controllers;

use App\Models\Task;
use OpenApi\Attributes as OA;
use App\Http\Responses\TaskResponse;
use App\Support\Http\Resources\Json\JsonResponseFactory;

class TaskController extends Controller
{
    public function __construct(private JsonResponseFactory $jsonResponse)
    {
    }

    /**
     * @return \App\Support\Http\Resources\Json\JsonResponse
     */
    #[OA\Get(
        path: '/api/tasks',
        operationId: 'getTasks',
        summary: 'List all tasks',
        description: 'Returns all tasks for the authenticated user (or all tasks if admin).',
        tags: ['Tasks'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of tasks retrieved successfully',
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
                                        new OA\Property(property: 'title', type: 'string', example: 'Fix login bug'),
                                        new OA\Property(
                                            property: 'description',
                                            type: 'string',
                                            example: 'Resolve error in login controller'
                                        ),
                                        new OA\Property(property: 'status', type: 'string', example: 'in_progress'),
                                        new OA\Property(property: 'user_id', type: 'integer', example: 1),
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
    public function index()
    {
        $tasks = Task::all();

        return $this->jsonResponse
            ->collection($tasks, new TaskResponse())
            ->create();
    }
}
