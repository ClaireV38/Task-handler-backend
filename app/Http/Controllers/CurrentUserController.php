<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use App\Support\Http\Resources\Json\JsonResponse;
use App\Http\Responses\CurrentUserResponse;
use App\Support\Http\Resources\Json\JsonResponseFactory;
use OpenApi\Attributes as OA;

final readonly class CurrentUserController
{
    public function __construct(
        #[CurrentUser('sanctum')] private User $user,
        private JsonResponseFactory $jsonResponse,
    ) {
    }

    #[OA\Get(
        path: "/api/me",
        operationId: "getCurrentUser",
        summary: "Get the currently authenticated user",
        security: [["bearerAuth" => []]],
        tags: ["User"]
    )]
    #[OA\Response(
        response: 200,
        description: "Authenticated user data"
    )]
    public function show(): JsonResponse
    {
        return $this->jsonResponse->item(
            $this->user,
            new CurrentUserResponse(),
        )->create();
    }
}
