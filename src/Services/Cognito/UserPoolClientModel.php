<?php

namespace Vng\EvaCore\Services\Cognito;

class UserPoolClientModel
{
    private string $clientId;
    private string $clientName;
    private string $userPoolId;

    public function __construct($properties)
    {
        $this->clientId = $properties['ClientId'];
        $this->clientName = $properties['ClientName'];
        $this->userPoolId = $properties['UserPoolId'];
    }

    public static function create($properties)
    {
        return new static($properties);
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getClientName(): string
    {
        return $this->clientName;
    }

    public function getUserPoolId(): string
    {
        return $this->userPoolId;
    }
}
