<?php

declare(strict_types=1);

namespace App\Support\Http\Resources\Json;

use Illuminate\Http\Request;
use League\Fractal\Pagination\Cursor;
use League\Fractal\TransformerAbstract;
use Illuminate\Pagination\CursorPaginator;
use League\Fractal\Pagination\PaginatorInterface;
use App\Support\Http\Resources\Json\JsonResources\JsonItem;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Support\Http\Resources\Json\JsonResources\JsonPaginated;
use App\Support\Http\Resources\Json\JsonResources\JsonCollection;
use App\Support\Http\Resources\Json\JsonResources\JsonCursorPaginated;

final class JsonResponseFactory
{
    public function __construct(
        private readonly Request $request,
        public readonly string $resourceName = 'data',
    ) {
    }

    /**
     * @template T
     *
     * @param T $data
     *
     * @return JsonItem<T>
     */
    public function item(mixed $data, TransformerAbstract $transformer): JsonItem
    {
        return new JsonItem($data, $transformer, $this->resourceName);
    }

    /**
     * @template T
     *
     * @param iterable<T> $data
     *
     * @return JsonCollection<T>
     */
    public function collection(iterable $data, TransformerAbstract $transformer): JsonCollection
    {
        return new JsonCollection($data, $transformer, $this->resourceName);
    }

    /**
     * @template T
     *
     * @param LengthAwarePaginator<int, T> $data
     *
     * @return JsonPaginated<T>
     */
    public function paginated(
        LengthAwarePaginator $data,
        PaginatorInterface $paginator,
        TransformerAbstract $transformer,
    ): JsonPaginated {
        return new JsonPaginated($data, $paginator, $transformer, $this->resourceName);
    }

    /**
     * @template T
     *
     * @param CursorPaginator<int, T> $paginator
     *
     * @return JsonCursorPaginated<T>
     */
    public function cursorPaginated(
        CursorPaginator $paginator,
        TransformerAbstract $transformer,
    ): JsonCursorPaginated {
        $currentCursor = $this->request->input('cursor') ?? null;

        if (! \is_string($currentCursor)) {
            $currentCursor = null;
        }

        $cursor = new Cursor(
            $currentCursor,
            $paginator->previousCursor()?->encode(),
            $paginator->nextCursor()?->encode(),
        );

        return new JsonCursorPaginated(
            $paginator,
            $cursor,
            $transformer,
            $this->resourceName,
            $this->request,
        );
    }
}
