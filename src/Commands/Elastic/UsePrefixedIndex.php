<?php

namespace Vng\EvaCore\Commands\Elastic;

trait UsePrefixedIndex
{
    public function getIndexPrefix(): ?string
    {
        $prefix = config('elastic.prefix');
        return $prefix ?: null;
    }

    public function prefixIndex($indexName)
    {
        $prefix = $this->getIndexPrefix();
        if (is_null($prefix)) {
            return $indexName;
        }
        return $prefix . '-' . $indexName;
    }
}