<?php


namespace Vng\EvaCore\Interfaces;


interface EvaUserInterface
{
    public function isSuperAdmin();
    public function assignRandomPassword();

    public function sendAccountCreationNotification();
    public function sendPasswordResetNotification();
}
