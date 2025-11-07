<?php

declare(strict_types=1);

namespace App\Http\Responses;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserResponse extends TransformerAbstract
{
    /** @return array<string, mixed> */
    public function transform(User $user): array
    {
        return [
            'id'         => $user->id,
            'name'       => $user->name,
            'email'      => $user->email,
            'created_at' => $user->created_at?->toIso8601String(),
        ];
    }
}
