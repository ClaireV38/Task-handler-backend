<?php

declare(strict_types=1);

namespace App\Support\Http\Resources\Json\JsonResources;

use League\Fractal\TransformerAbstract;
use App\Support\Http\Resources\Json\JsonResource;
use League\Fractal\Pagination\PaginatorInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/** @template T */
final class JsonPaginated extends JsonResource
{
    /** @param LengthAwarePaginator<int, T> $data */
    public function __construct(
        LengthAwarePaginator $data,
        PaginatorInterface $paginator,
        TransformerAbstract $transformer,
        string $resourceName,
    ) {
        parent::__construct();

        $this->fractal
            ->withResourceName($resourceName)
            ->collection($data, $transformer)
            ->paginateWith($paginator);
    }
}
