<?php

namespace Vng\EvaCore\Services\GeoData;

abstract class BasicGeoModel
{
    public string $code;
    public string $name;
    public string $slug;

    public static function create(): self
    {
        return new static();
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): BasicGeoModel
    {
        $this->code = $code;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): BasicGeoModel
    {
        $this->name = $name;
        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): BasicGeoModel
    {
        $this->slug = $slug;
        return $this;
    }
}
