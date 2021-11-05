<?php

namespace Vng\EvaCore\Interfaces;

interface SearchableInterface
{
    public function getSearchIndex();
    public function getSearchType();
    public function toSearchArray();
}
