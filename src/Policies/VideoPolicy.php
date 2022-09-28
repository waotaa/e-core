<?php

namespace Vng\EvaCore\Policies;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\Video;
use Illuminate\Auth\Access\HandlesAuthorization;

class VideoPolicy
{
    use HandlesAuthorization;

    public function viewAny(IsManagerInterface $user)
    {
        return true;
    }

    /**
     * @param IsManagerInterface&Authorizable $user
     * @param Video $video
     * @return mixed
     */
    public function view(IsManagerInterface $user, Video $video)
    {
        return $user->can('view', $video->instrument);
    }

    public function create(IsManagerInterface $user)
    {
        return true;
    }

    /**
     * @param IsManagerInterface&Authorizable $user
     * @param Video $video
     * @return mixed
     */
    public function update(IsManagerInterface $user, Video $video)
    {
        return $user->can('update', $video->instrument);
    }

    /**
     * @param IsManagerInterface&Authorizable $user
     * @param Video $video
     * @return mixed
     */
    public function delete(IsManagerInterface $user, Video $video)
    {
        return $user->can('update', $video->instrument);
    }
}
