<?php

namespace Vng\EvaCore\ElasticResources\SGR;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Vng\EvaCore\ElasticResources\ElasticResourceInterface;
use function value;

class ElasticResource implements ElasticResourceInterface
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
        return static::collection($collection)
            ->map(fn (ElasticResource $resource) => $resource->toArray())
            ->toArray();
    }

    public function toArray()
    {
        return $this->resource->toArray();
    }

    public function __get($key)
    {
        return $this->resource->{$key};
    }

    protected function whenLoaded($relationship, $value = null)
    {
        if (! $this->resource->relationLoaded($relationship)) {
            return null;
        }

        if (func_num_args() === 1) {
            return $this->resource->{$relationship};
        }

        if ($this->resource->{$relationship} === null) {
            return null;
        }

        return value($value);
    }

    protected function formatDate($date)
    {
        if ($date instanceof Carbon) {
            return $date->copy()->setTimezone('UTC')->format('Y-m-d\TH:i:s.u\Z');
        }
        return $date;
    }

}
