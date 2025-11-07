<?php

declare(strict_types=1);

namespace App\Support\Fractal\Serializers;

use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Pagination\PaginatorInterface;

class DefaultSerializer extends ArraySerializer
{
    /**
     * @param array<string|int, mixed> $data
     *
     * @return array<string|int, mixed>
     */
    #[\Override]
    public function item(?string $resourceKey, array $data) : array
    {
        return $resourceKey === '' || $resourceKey === null ? $data : [$resourceKey => $data];
    }

    /**
     * @param array<string|int, mixed> $data
     *
     * @return array<string|int, mixed>
     */
    #[\Override]
    public function collection(?string $resourceKey, array $data) : array
    {
        return $resourceKey === '' || $resourceKey === null ? $data : [$resourceKey => $data];
    }

    /** @return array<string|int, mixed>|null */
    #[\Override]
    public function null() : ?array
    {
        return null;
    }

    /** @return array<string|int, mixed> */
    #[\Override]
    public function paginator(PaginatorInterface $paginator) : array
    {
        $pagination = parent::paginator($paginator);

        if (! isset($pagination['pagination']) || ! \is_array($pagination['pagination'])) {
            return [];
        }

        $entry = $pagination['pagination'];

        if (! isset($entry['links']) || \is_object($entry['links'])) {
            $pagination['pagination']['links'] = $this->null();
        }

        return $pagination;
    }
}
