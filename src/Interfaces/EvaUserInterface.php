<?php


namespace Vng\EvaCore\Interfaces;


interface EvaUserInterface
{
    public function isSuperAdmin();
    public function isAdministrator();

    public function getName(): ?string;
    public function getGivenName(): ?string;
    public function getSurName(): ?string;
    public function getEmail(): ?string;

    public function assignRandomPassword();
    public function sendAccountCreationNotification();
    public function sendPasswordResetNotification($token);
}
