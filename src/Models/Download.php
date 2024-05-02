<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Vng\EvaCore\Observers\DownloadObserver;
use Vng\EvaCore\Services\Storage\DownloadStorageService;
use Vng\EvaCore\Traits\HasOwner;

class Download extends Model
{
    use HasOwner;

    protected $table = 'downloads';

    protected $fillable = [
        'label',
        'url',
        'filename'
    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(DownloadObserver::class);
    }

    public function delete($deleteFile = true)
    {
        if ($deleteFile) {
            try {
                DownloadStorageService::make()->deleteFile($this->url);
            } catch (\Exception $e) {
                // accept for now that deleting the file failed.
                // We still want to delete the download entity though
            }
        }
        return parent::delete();
    }

    /**
     * @deprecated remove after migration
     */
    public function instrument(): BelongsTo
    {
        return $this->belongsTo(Instrument::class);
    }

    public function instruments(): BelongsToMany
    {
        return $this->belongsToMany(Instrument::class, 'download_instrument')
            ->withTimestamps();
    }
}
