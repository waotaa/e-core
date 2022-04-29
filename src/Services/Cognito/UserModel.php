<?php

namespace Vng\EvaCore\Services\Cognito;

use Carbon\Carbon;
use DateTime;
use Exception;

class UserModel
{
    private DateTime $userCreateDate;
    private string $username;
    private string $email;
    private bool $emailVerified;
    private bool $enabled;
    private string $userStatus;
    private ?DateTime $passwordUpdatedAt;
    private array $attributes;

    public function __construct($properties)
    {
        $this->attributes = $this->findAttributes($properties);

        $email = $this->getAttributeValue('email');
        if (!$email) {
            throw new Exception('Email required - None present in attributes');
        }

        $this->userCreateDate = $properties['UserCreateDate'];
        $this->username = $properties['Username'];
        $this->email = $email;
        $this->emailVerified = $this->getAttributeValue('email_verified') ?? false;
        $this->enabled = $properties['Enabled'];
        $this->userStatus = $properties['UserStatus'];
        $this->passwordUpdatedAt = $this->getPasswordUpdatedAt();
    }

    private function findAttributes($properties): array
    {
        if ($properties['Attributes']) {
            return $properties['Attributes'];
        }
        if ($properties['UserAttributes']) {
            return $properties['UserAttributes'];
        }
        return [];
    }

    private function getPasswordUpdatedAt(): ?DateTime
    {
        $passwordUpdatedAt = $this->getAttributeValue('custom:password_updated_at') ?? null;
        if (is_null($passwordUpdatedAt)) {
            return null;
        }
        return new DateTime($passwordUpdatedAt);
    }

    private function getAttributeValue($attribute): ?string
    {
        $attributes = array_filter($this->attributes, fn($a) => $a['Name'] === $attribute);
        if (!count($attributes)){
            return null;
        }
        $attributeEntry = reset($attributes);
        return $attributeEntry['Value'];
    }

    public static function create($properties)
    {
        return new static($properties);
    }

    public function getUserCreateDate(): DateTime
    {
        return $this->userCreateDate;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function isEmailVerified(): bool
    {
        return $this->emailVerified;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function getUserStatus(): string
    {
        return $this->userStatus;
    }

    public function getPasswordUpdatedAtDate(): ?DateTime
    {
        return $this->passwordUpdatedAt;
    }

    public function isPasswordExpired(): bool
    {
        $sixMonthsAgo = Carbon::now()->modify('-6 months');
        $statusAllowsReset = $this->getUserStatus() !== 'FORCE_CHANGE_PASSWORD';
        $isExpired = $this->getPasswordUpdatedAtDate() < $sixMonthsAgo;
        return $statusAllowsReset && $isExpired;
    }
}
