<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

trait MutationLog
{
    public function actions()
    {
        return $this->morphMany(Mutation::class, 'loggable');
    }

    public static function bootMutationLog() {
        static::created(function($model) {
            /** @var User $user */
            $user = Auth::user();
            Mutation::forResourceCreate($user->manager, $model);
        });

        static::updated(function($model) {
            /** @var User $user */
            $user = Auth::user();
            Mutation::forResourceUpdate($user->manager, $model);
        });

        static::deleted(function($model) {
            if (in_array(SoftDeletes::class, class_uses($model))) {
                /** @var User $user */
                $user = Auth::user();
                Mutation::forResourceDelete($user->manager, $model);
            }
        });

        static::restored(function($model) {
            if (in_array(SoftDeletes::class, class_uses($model))) {
                /** @var User $user */
                $user = Auth::user();
                Mutation::forResourceRestore($user->manager, $model);
            }
        });
    }
}
