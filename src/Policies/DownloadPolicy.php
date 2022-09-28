<?php

namespace Vng\EvaCore\Policies;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\Download;
use Illuminate\Auth\Access\HandlesAuthorization;

class DownloadPolicy
{
    use HandlesAuthorization;

    public function viewAny(IsManagerInterface $user)
    {
        return true;
    }

    /**
     * @param IsManagerInterface&Authorizable $user
     * @param Download $download
     * @return mixed
     */
    public function view(IsManagerInterface $user, Download $download)
    {
        return $user->can('view', $download->instrument);
    }

    public function create(IsManagerInterface $user)
    {
        return true;
    }

    /**
     * @param IsManagerInterface&Authorizable $user
     * @param Download $download
     * @return mixed
     */
    public function update(IsManagerInterface $user, Download $download)
    {
        return $user->can('update', $download->instrument);
    }

    /**
     * @param IsManagerInterface&Authorizable $user
     * @param Download $download
     * @return mixed
     */
    public function delete(IsManagerInterface $user, Download $download)
    {
        return $user->can('update', $download->instrument);
    }
}
