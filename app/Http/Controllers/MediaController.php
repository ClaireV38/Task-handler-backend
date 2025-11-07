<?php

namespace App\Http\Controllers;

use App\Http\Responses\MediaResponse;
use App\Support\Http\Resources\Json\JsonResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Media', description: 'Media file handling for tasks')]
class MediaController extends Controller
{
    public function __construct(
        private JsonResponseFactory $jsonResponse,
        private readonly \Illuminate\Contracts\Auth\Access\Gate $gate
    ) {
    }

    /**
     * @return \App\Support\Http\Resources\Json\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    #[OA\Post(
        path: '/api/tasks/{taskId}/media',
        summary: 'Uploader un fichier média pour une tâche',
        tags: ['Media'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    required: ['file'],
                    properties: [
                        new OA\Property(
                            property: 'file',
                            description: 'Fichier à uploader (max 10 MB)',
                            type: 'string',
                            format: 'binary'
                        )
                    ]
                )
            )
        ),
        parameters: [
            new OA\Parameter(
                name: 'taskId',
                in: 'path',
                required: true,
                description: 'ID de la tâche associée',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Fichier uploadé avec succès',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'integer'),
                        new OA\Property(property: 'file_name', type: 'string'),
                        new OA\Property(property: 'mime_type', type: 'string'),
                        new OA\Property(property: 'url', type: 'string')
                    ]
                )
            ),
            new OA\Response(response: 403, description: 'Non autorisé'),
            new OA\Response(response: 422, description: 'Validation échouée'),
        ]
    )]
    public function store(Request $request, int $taskId)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10 MB
        ]);

        $task = \App\Models\Task::findOrFail($taskId);
        $this->gate->authorize('update', $task);

        $media = $task->addMediaFromRequest('file')
            ->toMediaCollection('attachments');


        return $this->jsonResponse->item(
            $media,
            new MediaResponse(),
        )->create();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    #[OA\Get(
        path: '/api/media/{mediaId}',
        summary: 'Lire ou streamer un fichier média',
        tags: ['Media'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'mediaId',
                in: 'path',
                required: true,
                description: 'ID du média à lire',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Flux vidéo ou fichier binaire',
                content: new OA\MediaType(
                    mediaType: 'application/octet-stream',
                    schema: new OA\Schema(type: 'string', format: 'binary')
                )
            ),
            new OA\Response(response: 403, description: 'Non autorisé à accéder à ce média'),
            new OA\Response(response: 404, description: 'Média introuvable'),
        ]
    )]
    public function show(Media $media)
    {
        $model = $media->model;
        $this->gate->authorize('update', $model);

        $disk = Storage::disk($media->disk);
        if (!$disk->exists($media->getPathRelativeToRoot())) {
            abort(404);
        }

        $stream = $disk->readStream($media->getPathRelativeToRoot());

        if ($stream === null) {
            abort(404);
        }

        return response()->stream(function () use ($stream) {
            fpassthru($stream);
        }, 200, [
            'Content-Type'        => $media->mime_type ?? 'application/octet-stream',
            'Content-Disposition' => 'inline; filename="' . $media->file_name . '"',
            'Cache-Control' => 'private, max-age=0, no-cache, no-store',
            'Pragma'        => 'no-cache',
            'ETag'          => md5($media->id . $media->updated_at),
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    #[OA\Delete(
        path: '/api/media/{mediaId}',
        summary: 'Supprimer un fichier média',
        tags: ['Media'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'mediaId',
                in: 'path',
                required: true,
                description: 'ID du média à supprimer',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Fichier supprimé avec succès',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Deleted successfully')
                    ]
                )
            ),
            new OA\Response(response: 403, description: 'Non autorisé'),
            new OA\Response(response: 404, description: 'Média introuvable'),
        ]
    )]
    public function destroy(Media $media)
    {
        $model = $media->model;
        $this->gate->authorize('update', $model);

        $media->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
