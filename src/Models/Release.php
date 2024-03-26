<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Release extends Model
{
    const STATUS_NEW = 'new';
    const STATUS_PLANNED = 'planned';
    const STATUS_OVERDUE = 'overdue';
    const STATUS_RELEASED = 'released';

    protected $table = 'releases';

    protected $fillable = [
        'version',
        'planned_at',
        'released_at'
    ];

    protected $casts = [
        'changes' => 'array'
    ];

    protected $dates = [
        'planned_at',
        'released_at'
    ];

    public function changes(): HasMany
    {
        return $this->hasMany(ReleaseChange::class);
    }

    public function releaseIsOverdue(): bool
    {
        if (is_null($this->planned_at)) {
            return false;
        }
        $plannedAtDate = Carbon::parse($this->planned_at);
        return $plannedAtDate->isToday() || $plannedAtDate->isPast();
    }

    /**
     * Controleert of de release daadwerkelijk is uitgebracht (vandaag of in het verleden).
     *
     * @return bool
     */
    public function isReleased(): bool
    {
        if (is_null($this->released_at)) {
            return false;
        }
        $releasedAtDate = Carbon::parse($this->released_at);
        return $releasedAtDate->isToday() || $releasedAtDate->isPast();
    }

    public function getStatus(): string
    {
        if ($this->isReleased()) {
            return self::STATUS_RELEASED;
        }
        if (!is_null($this->planned_at)) {
            if ($this->releaseIsOverdue()) {
                return self::STATUS_OVERDUE;
            }
            return self::STATUS_PLANNED;
        }
        return self::STATUS_NEW;
    }
}
