<?php

namespace Vng\EvaCore\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\Faq;

class FaqPolicy
{
    use HandlesAuthorization;

    public function viewAny(IsManagerInterface $user): bool
    {
        return true; // Everyone can view FAQs
    }

    public function view(IsManagerInterface $user, Faq $faq): bool
    {
        return true; // Everyone can view individual FAQs
    }

    public function create(IsManagerInterface $user): bool
    {
        return $user->managerCan('faq.create');
    }

    public function update(IsManagerInterface $user, Faq $faq): bool
    {
        return $user->managerCan('faq.update');
    }

    public function delete(IsManagerInterface $user, Faq $faq): bool
    {
        return $user->managerCan('faq.delete');
    }
}
