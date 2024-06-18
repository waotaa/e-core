<?php

namespace Vng\EvaCore\Policies;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\Download;
use Illuminate\Auth\Access\HandlesAuthorization;

class DownloadPolicy extends InstrumentPropertyPolicy
{
    use HandlesAuthorization;

    public function viewAny(IsManagerInterface $user)
    {
        return $user->managerCan('download.viewAny');
    }

    public function viewAll(IsManagerInterface $user)
    {
        return $user->managerCan('download.viewAll');
    }

    /**
     * @param IsManagerInterface&Authorizable $user
     * @param Download $download
     * @return mixed
     */
    public function view(IsManagerInterface $user, Download $download)
    {
        if ($download->hasOwner()
            && $user->managerCan('download.organisation.view')
            && $download->isUserMemberOfOwner($user)
        ) {
            return true;
        }
        return $user->managerCan('download.view') || $this->viewAll($user);
    }

    public function create(IsManagerInterface $user)
    {
        return $user->managerCan('download.organisation.create')
            || $user->managerCan('download.create');
    }

    /**
     * @param IsManagerInterface&Authorizable $user
     * @param Download $download
     * @return mixed
     */
    public function update(IsManagerInterface $user, Download $download)
    {
        if ($download->hasOwner()
            && $user->managerCan('download.organisation.update')
            && $download->isUserMemberOfOwner($user)
        ) {
            return true;
        }
        return $user->managerCan('download.update');
    }

    /**
     * @param IsManagerInterface&Authorizable $user
     * @param Download $download
     * @return mixed
     */
    public function delete(IsManagerInterface $user, Download $download)
    {
        if ($download->hasOwner()
            && $user->managerCan('download.organisation.delete')
            && $download->isUserMemberOfOwner($user)
        ) {
            return true;
        }
        return $user->managerCan('download.delete');
    }
}
