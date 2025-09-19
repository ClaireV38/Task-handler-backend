<?php

declare(strict_types=1);

namespace App\Support\Http\Resources\Json\JsonResources;

use Illuminate\Http\Request;
use League\Fractal\TransformerAbstract;
use Illuminate\Pagination\CursorPaginator;
use League\Fractal\Pagination\CursorInterface;
use App\Support\Http\Resources\Json\JsonResource;
use App\Support\Http\Resources\Json\JsonResponse;

/**
 * @template T
 */
final class JsonCursorPaginated extends JsonResource
{
    private ?int $archivedCount = null;

    private CursorInterface $cursor;

    /** @var CursorPaginator<int, T> */
    private CursorPaginator $data;

    /** @var array<mixed, mixed> */
    private array $queryParams;

    /**
     * @param CursorPaginator<int, T> $data
     */
    public function __construct(
        CursorPaginator $data,
        CursorInterface $cursor,
        TransformerAbstract $transformer,
        string $resourceName,
        Request $request,
    ) {
        parent::__construct();

        $queryParams = $request->query();
        unset($queryParams['cursor']);

        $meta = [
            'pagination' => [
                'count'    => $data->count(),
                'per_page' => $data->perPage(),
                'cursor'   => [
                    'current'  => $cursor->getCurrent(),
                    'next'     => $cursor->getNext(),
                    'previous' => $cursor->getPrev(),
                ],
                'links' => [
                    'next'     => $data->nextPageUrl() !== null
                        ? $data->nextPageUrl() . '&' . http_build_query($queryParams)
                        : null,
                    'previous' => $data->previousPageUrl() !== null
                        ? $data->previousPageUrl() . '&' . http_build_query($queryParams)
                        : null,
                ],
            ],
        ];

        if ($this->archivedCount !== null) {
            $meta['archived'] = [
                'count' => $this->archivedCount,
            ];
        }

        $this->fractal
            ->collection($data->items(), $transformer, $resourceName);

        $this->cursor = $cursor;
        $this->data = $data;
        $this->queryParams = $queryParams;
    }

    /**
     *  @return JsonCursorPaginated<T>
     */
    public function withArchivedCount(int $count) : self
    {
        $this->archivedCount = $count;

        return $this;
    }

    #[\Override]
    /**
     * @return JsonResponse<T>
     */
    public function create() : JsonResponse
    {
        $meta = [
            'pagination' => [
                'count'    => $this->data->count(),
                'per_page' => $this->data->perPage(),
                'cursor'   => [
                    'current'  => $this->cursor->getCurrent(),
                    'next'     => $this->cursor->getNext(),
                    'previous' => $this->cursor->getPrev(),
                ],
                'links' => [
                    'next'     => $this->data->nextPageUrl() !== null
                        ? $this->data->nextPageUrl() . '&' . http_build_query($this->queryParams)
                        : null,
                    'previous' => $this->data->previousPageUrl() !== null
                        ? $this->data->previousPageUrl() . '&' . http_build_query($this->queryParams)
                        : null,
                ],
            ],
        ];

        if ($this->archivedCount !== null) {
            $meta['archived'] = [
                'count' => $this->archivedCount,
            ];
        }

        $this->fractal->addMeta($meta);

        return parent::create();
    }
}
