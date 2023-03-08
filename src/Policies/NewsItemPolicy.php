<?php

namespace Vng\EvaCore\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\NewsItem;

class NewsItemPolicy extends BasePolicy
{
    use HandlesAuthorization;

    /**
     * @param IsManagerInterface&Authorizable $user
     * @return mixed
     */
    public function viewAny(IsManagerInterface $user)
    {
        return $user->managerCan('newsItem.viewAny');
    }
    /**
     * @param IsManagerInterface&Authorizable $user
     * @return mixed
     */
    public function viewAll(IsManagerInterface $user)
    {
        return $user->managerCan('newsItem.viewAll');
    }

    /**
     * @param IsManagerInterface&Authorizable $user
     * @param NewsItem $newsItem
     * @return bool
     */
    public function view(IsManagerInterface $user, NewsItem $newsItem)
    {
        $environment = $newsItem->environment;
        if ($environment->hasOwner()
            && $environment->isUserMemberOfOwner($user)
            && $user->managerCan('newsItem.view')
        ) {
            return true;
        }
        return $this->viewAll($user);
    }

    public function create(IsManagerInterface $user)
    {
        return $user->managerCan('newsItem.create');
    }

    /**
     * @param IsManagerInterface&Authorizable $user
     * @param NewsItem $newsItem
     * @return bool
     */
    public function update(IsManagerInterface $user, NewsItem $newsItem)
    {
        $environment = $newsItem->environment;
        return $environment->hasOwner()
            && $environment->isUserMemberOfOwner($user)
            && $user->managerCan('newsItem.update');
    }

    /**
     * @param IsManagerInterface&Authorizable $user
     * @param NewsItem $newsItem
     * @return bool
     */
    public function delete(IsManagerInterface $user, NewsItem $newsItem)
    {
        $environment = $newsItem->environment;
        return $environment->hasOwner()
            && $environment->isUserMemberOfOwner($user)
            && $user->managerCan('newsItem.delete');
    }
}
