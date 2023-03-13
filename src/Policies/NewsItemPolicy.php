<?php

namespace Vng\EvaCore\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\Environment;
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
        /** @var Environment $environment */
        $environment = $newsItem->environment;
        if (
            !is_null($environment)
            && $environment->hasOwner()
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
        /** @var Environment $environment */
        $environment = $newsItem->environment;
        return !is_null($environment)
            && $environment->hasOwner()
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
        /** @var Environment $environment */
        $environment = $newsItem->environment;
        return !is_null($environment)
            && $environment->hasOwner()
            && $environment->isUserMemberOfOwner($user)
            && $user->managerCan('newsItem.delete');
    }
}
