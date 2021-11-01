<?php

namespace Vng\EvaCore\ElasticResources;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ElasticResource
{
    protected $resource;

    public function __construct(Model $resource)
    {
        $this->resource = $resource;
    }

    public static function make(Model $resource)
    {
        return new static($resource);
    }

    public static function collection(Collection $collection)
    {
        return $collection->map(fn (Model $resource) => static::make($resource));
    }

    public static function one(?Model $resource = null)
    {
        if (is_null($resource)) {
            return null;
        }
        return static::make($resource)->toArray();
    }

    public static function many(?Collection $collection = null)
    {
        if (is_null($collection)) {
            return null;
        }
        return static::collection($collection)->map(fn (ElasticResource $resource) => $resource->toArray())->toArray();
    }

    public function toArray()
    {
        return $this->resource->toArray();
    }

    public function __get($key)
    {
        return $this->resource->{$key};
    }
}
