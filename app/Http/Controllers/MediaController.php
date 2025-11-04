<?php


namespace App\Http\Controllers;

use App\Http\Responses\MediaResponse;
use App\Support\Http\Resources\Json\JsonResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use OpenApi\Attributes as OA;

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
            'Content-Type' => $media->mime_type ?? 'application/octet-stream',
            'Content-Disposition' => 'inline; filename="' . $media->file_name . '"',
// Empêche le cache navigateur tout en permettant Cloudflare
            'Cache-Control' => 'private, max-age=0, no-cache, no-store',
            'Pragma' => 'no-cache',
            'ETag' => md5($media->id . $media->updated_at),
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Media $media)
    {
        $model = $media->model;
        $this->gate->authorize('update', $model);

        $media->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
