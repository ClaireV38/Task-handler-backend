<?php

declare(strict_types=1);

namespace App\Http\Responses;

use App\Models\Task;
use League\Fractal\TransformerAbstract;

class TaskResponse extends TransformerAbstract
{
    /** @return array<string, mixed> */
    public function transform(Task $task): array
    {
        return [
            'id'         => $task->id,
            'title'      => $task->title,
            'status'     => $task->status,
            'created_at' => $task->created_at?->toIso8601String(),
            'media' => $task->getMedia('attachments')->map(fn($media) => [
                'id' => $media->id,
                'file_name' => $media->file_name,
                'mime_type' => $media->mime_type,
                'size' => $media->size,
                'url' => route('media.show', $media),
            ]),
        ];
    }
}
