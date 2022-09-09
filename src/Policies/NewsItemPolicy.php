<?php

namespace Vng\EvaCore\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\NewsItem;

class NewsItemPolicy extends BasePolicy
{
    use HandlesAuthorization;

    public function viewAny(IsManagerInterface $user)
    {
        return $user->managerCan('newsItem.viewAny');
    }

    public function view(IsManagerInterface $user, NewsItem $newsItem)
    {
        return $user->managerCan('newsItem.view');
    }

    public function create(IsManagerInterface $user)
    {
        return $user->managerCan('newsItem.create');
    }

    public function update(IsManagerInterface $user, NewsItem $newsItem)
    {
        return $user->managerCan('newsItem.update');
    }

    public function delete(IsManagerInterface $user, NewsItem $newsItem)
    {
        return $user->managerCan('newsItem.delete');
    }

    public function restore(IsManagerInterface $user, NewsItem $newsItem)
    {
        return $user->managerCan('newsItem.restore');
    }

    public function forceDelete(IsManagerInterface $user, NewsItem $newsItem)
    {
        return $user->managerCan('newsItem.forceDelete');
    }
}
