<?php

declare(strict_types=1);

namespace App\Support\Http\Resources\Json\JsonResources;

use League\Fractal\TransformerAbstract;
use App\Support\Http\Resources\Json\JsonResource;

/** @template T */
final class JsonCollection extends JsonResource
{
    /** @param iterable<T> $data */
    public function __construct(
        iterable $data,
        TransformerAbstract $transformer,
        string $resourceName,
    ) {
        parent::__construct();

        $this->fractal->withResourceName($resourceName)->collection($data, $transformer);
    }
}
