<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use App\Support\Http\Resources\Json\JsonResponse;
use App\Http\Responses\CurrentUserResponse;
use App\Support\Http\Resources\Json\JsonResponseFactory;

final readonly class CurrentUserController
{
    public function __construct(
        #[CurrentUser('sanctum')] private User $user,
        private JsonResponseFactory $jsonResponse,
    ) {
    }

    public function show(): JsonResponse
    {
        return $this->jsonResponse->item(
            $this->user,
            new CurrentUserResponse(),
        )->create();
    }
}
