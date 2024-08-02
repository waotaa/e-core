<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Model;
use Vng\EvaCore\Observers\ExportObserver;
use Vng\EvaCore\Services\Storage\ExportStorageService;
use Vng\EvaCore\Traits\HasOwner;

class Export extends Model
{
    use HasOwner;

    const TYPE_INSTRUMENTS = 'instruments';

    const STATUS_INITIATED = 'initiated';
    const STATUS_FAILED = 'failed';
    const STATUS_DONE = 'done';

    protected $table = 'exports';

    protected $fillable = [
        'label',

        'type',
        'mark',

        'status',
        'progress',

        'file'
    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(ExportObserver::class);
    }

    public function delete($deleteFile = true)
    {
        if ($deleteFile) {
            try {
                ExportStorageService::make()->deleteFile($this->filename);
            } catch (\Exception $e) {
                // accept for now that deleting the file failed.
                // We still want to delete the download entity though
            }
        }
        return parent::delete();
    }
}
