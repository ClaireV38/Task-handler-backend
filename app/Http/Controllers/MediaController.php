<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaController extends Controller
{
    public function __construct(
        private readonly \Illuminate\Contracts\Auth\Access\Gate $gate,
    ) {
    }

    /**
     * Upload d’un média sur une Task.
     */
    public function store(Request $request, $taskId)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10 MB
        ]);

        $task = \App\Models\Task::findOrFail($taskId);
        $this->gate->authorize('update', $task);

        $media = $task->addMediaFromRequest('file')
            ->toMediaCollection('attachments');

        return response()->json([
            'id' => $media->id,
            'file_name' => $media->file_name,
            'mime_type' => $media->mime_type,
            'size' => $media->size,
            'created_at' => $media->created_at,
        ]);
    }

    /**
     * Récupération sécurisée du média via proxy Laravel.
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
     * Suppression d’un média.
     */
    public function destroy(Media $media)
    {
        $model = $media->model;
        $this->gate->authorize('update', $model);

        $media->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
