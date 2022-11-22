<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

trait MutationLog
{
    public function mutations()
    {
        return $this->morphMany(Mutation::class, 'loggable');
    }

    public static function bootMutationLog() {
        static::created(function($model) {
            /** @var User $user */
            $user = Auth::user();
            if (is_null($user)) {
                return;
            }
            $mutation = Mutation::forResourceCreate($user->manager, $model);
            $mutation->save();
        });

        static::updated(function($model) {
            /** @var User $user */
            $user = Auth::user();
            if (is_null($user)) {
                return;
            }
            $mutation = Mutation::forResourceUpdate($user->manager, $model);
            $mutation->save();
        });

        if (in_array(SoftDeletes::class, class_uses(static::class))) {
            static::deleted(function($model) {
                /** @var User $user */
                $user = Auth::user();
                if (is_null($user)) {
                    return;
                }
                $mutation = Mutation::forResourceDelete($user->manager, $model);
                $mutation->save();
            });
            static::restored(function($model) {
                /** @var User $user */
                $user = Auth::user();
                if (is_null($user)) {
                    return;
                }
                $mutation = Mutation::forResourceRestore($user->manager, $model);
                $mutation->save();
            });
        }

    }
}
