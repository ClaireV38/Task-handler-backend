<?php

declare(strict_types=1);

namespace App\Support\Http\Resources\Json;

use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse as IlluminateJsonResponse;

class JsonResponse implements Responsable
{
    /**
     * @param list<string> $includes
     * @param list<string> $headers
     */
    public function __construct(
        public readonly JsonResource $resource,
        public array $includes = [],
        public array $headers = [],
        public int $statusCode = Response::HTTP_OK,
    ) {}

    /** @throws \InvalidArgumentException */
    #[\Override]
    public function toResponse($request) : Response // @phpstan-ignore typeCoverage.paramTypeCoverage
    {
        $response = new IlluminateJsonResponse();

        $response->setStatusCode($this->statusCode);
        $response->setData($this->resource->getData($this->includes));
        $response->withHeaders($this->headers);

        return $response;
    }

    public function asCreated() : static
    {
        $this->statusCode = Response::HTTP_CREATED;

        return $this;
    }

    /** @param list<string> $headers */
    public function withHeaders(array $headers) : static
    {
        $this->headers = [...$this->headers, ...$headers];

        return $this;
    }

    /** @param list<string> $headers */
    public function replaceHeaders(array $headers) : static
    {
        $this->headers = $headers;

        return $this;
    }

    public function withStatusCode(int $statusCode) : static
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function addInclude(string $include) : static
    {
        $this->includes[] = $include;

        return $this;
    }

    /** @param list<string> $includes */
    public function replaceIncludes(array $includes) : static
    {
        $this->includes = $includes;

        return $this;
    }
}
