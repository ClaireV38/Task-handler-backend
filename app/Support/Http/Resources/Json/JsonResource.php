<?php

declare(strict_types=1);

namespace App\Support\Http\Resources\Json;

use Spatie\Fractal\Fractal;
use Spatie\Fractalistic\Fractal as Fractalistic;
use App\Support\Http\Resources\Json\Contracts\JsonResource as JsonResourceInterface;

abstract class JsonResource implements JsonResourceInterface
{
    protected Fractalistic $fractal;

    public function __construct()
    {
        $this->fractal = Fractal::create();
    }

    #[\Override]
    public function withMeta(array $meta): static
    {
        $this->fractal->addMeta($meta);

        return $this;
    }

    #[\Override]
    public function getData(array $includes): array
    {
        return $this->fractal->parseIncludes($includes)->toArray() ?? [];
    }

    #[\Override]
    public function create(): JsonResponse
    {
        return new JsonResponse($this);
    }
}
