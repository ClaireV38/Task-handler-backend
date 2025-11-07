<?php

declare(strict_types=1);

namespace App\Http\Responses;

use App\Models\Task;
use Illuminate\Support\Facades\URL;
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
            'user_id'    => $task->user_id,
            'media' => $task->getMediaResponseAttribute()->map(function ($media) {
                /*   //Temporary url if needed for cache
                     $temporaryUrl = URL::temporarySignedRoute(
                     'media.show',
                     now()->addMinutes(10),
                     ['media' => $media->id]
                 );  */

                return [
                    'id' => $media->id,
                    'file_name' => $media->file_name,
                    'mime_type' => $media->mime_type,
                    'size' => $media->size,
                    'url' => route('media.show', $media),
                ];
            })
        ];
    }
}
