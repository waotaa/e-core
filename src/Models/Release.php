<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Model;

class Release extends Model
{
    const STATUS_INITIATED = 'initiated';
    const STATUS_TASK_DONE = 'task-done';
    const STATUS_COMPLETED = 'completed';

    protected $table = 'releases';

    protected $fillable = [
        'version',
        'tasks',
        'status',
    ];

    protected $casts = [
        'tasks' => 'array'
    ];
}
