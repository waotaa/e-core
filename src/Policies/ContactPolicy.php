<?php

namespace Vng\EvaCore\Policies;

use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\Contact;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContactPolicy extends BasePolicy
{
    use HandlesAuthorization;

    public function viewAny(IsManagerInterface $user)
    {
        return $user->managerCan('contact.viewAny');
    }

    public function viewAll(IsManagerInterface $user)
    {
        return $user->managerCan('contact.viewAll');
    }

    public function view(IsManagerInterface $user, Contact $contact)
    {
        if ($contact->hasOwner()
            && $user->managerCan('contact.organisation.view')
            && $contact->isUserMemberOfOwner($user)
        ) {
            return true;
        }
        return $user->managerCan('contact.view') || $this->viewAll($user);
    }

    public function create(IsManagerInterface $user)
    {
        return $user->managerCan('contact.organisation.create')
            || $user->managerCan('contact.create');
    }

    public function update(IsManagerInterface $user, Contact $contact)
    {
        if ($contact->hasOwner()
            && $user->managerCan('contact.organisation.update')
            && $contact->isUserMemberOfOwner($user)
        ) {
            return true;
        }
        return $user->managerCan('contact.update');
    }

    public function delete(IsManagerInterface $user, Contact $contact)
    {
        if ($contact->hasOwner()
            && $user->managerCan('contact.organisation.delete')
            && $contact->isUserMemberOfOwner($user)
        ) {
            return true;
        }
        return $user->managerCan('contact.delete');
    }

    // attach instrument
    // detach instrument

    // attach provider
    // detach provider
}
