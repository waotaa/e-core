<?php

namespace Vng\EvaCore\Services\Cognito;

use DateTime;

class UserPoolModel
{
    private DateTime $creationDate;
    private string $id;
    private string $name;

    public function __construct($properties)
    {
        $this->creationDate = $properties['CreationDate'];
        $this->id = $properties['Id'];
        $this->name = $properties['Name'];
    }

    public static function create($properties)
    {
        return new static($properties);
    }

    public function getCreationDate(): DateTime
    {
        return $this->creationDate;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
