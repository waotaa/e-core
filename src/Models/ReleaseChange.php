<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Vng\EvaCore\Services\Storage\ReleaseImageStorageService;

class ReleaseChange extends Model
{
    protected $table = 'release_changes';

    protected $fillable = [
        'title',
        'description',
        'image'
    ];

    public function release(): BelongsTo
    {
        return $this->belongsTo(Release::class);
    }

    public function getImageUrlAttribute()
    {
        return ReleaseImageStorageService::make()
            ->setRelease($this->release)
            ->getFileUrl($this->attributes['image']);
    }
}
