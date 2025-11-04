<?php

declare(strict_types=1);

namespace App\Http\Responses;

use League\Fractal\TransformerAbstract;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaResponse extends TransformerAbstract
{
    /** @return array<string, mixed> */
    public function transform(Media $media): array
    {
        return [
            'id' => $media->id,
            'file_name' => $media->file_name,
            'size' => $media->size,
            'created_at' => $media->created_at?->toIso8601String(),
        ];
    }
}
