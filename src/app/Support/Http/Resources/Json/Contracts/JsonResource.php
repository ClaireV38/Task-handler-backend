<?php

declare(strict_types=1);

namespace App\Support\Http\Resources\Json\Contracts;

use App\Support\Http\Resources\Json\JsonResponse;

interface JsonResource
{
    /** @param array<mixed, mixed> $meta */
    public function withMeta(array $meta) : static;

    /**
     * @param list<string> $includes
     *
     * @return array<string|int, mixed>
     */
    public function getData(array $includes) : array;

    public function create() : JsonResponse;
}
