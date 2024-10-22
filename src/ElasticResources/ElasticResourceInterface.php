<?php

namespace Vng\EvaCore\ElasticResources;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface ElasticResourceInterface
{
    public static function make(Model $resource);

    public static function collection(Collection $collection);

    public static function one(?Model $resource = null);

    public static function many(?Collection $collection = null);

    public function toArray();

    public function __get($key);
}
