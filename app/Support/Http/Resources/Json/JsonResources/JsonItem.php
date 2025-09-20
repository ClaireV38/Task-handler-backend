<?php

declare(strict_types=1);

namespace App\Support\Http\Resources\Json\JsonResources;

use League\Fractal\TransformerAbstract;
use App\Support\Http\Resources\Json\JsonResource;

/** @template T */
final class JsonItem extends JsonResource
{
    /** @param T $data */
    public function __construct(mixed $data, TransformerAbstract $transformer, string $resourceName)
    {
        parent::__construct();

        $this->fractal->withResourceName($resourceName)->item($data, $transformer);
    }

    /**
     * @param array<string>|string $includes
     *
     * @return JsonItem<T>
     */
    public function parseIncludes(array|string $includes): self
    {
        $this->fractal->parseIncludes($includes);

        return $this;
    }
}
