<?php

namespace Vng\EvaCore\Interfaces;

interface Searchable
{
    public function getSearchIndex();
    public function getSearchType();
    public function toSearchArray();
}
