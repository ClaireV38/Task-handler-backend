<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\URL;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Task extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'description',
        'completed',
        'user_id',
    ];

    protected $casts = [
        'status' => TaskStatus::class,
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments')
            ->useDisk('media')
            ->onlyKeepLatest(10);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media>
     */
    public function getMediaResponseAttribute()
    {
        return $this->getMedia('attachments')->map(function ($media) {
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
        });
    }
}
