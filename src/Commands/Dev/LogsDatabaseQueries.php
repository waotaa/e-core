<?php

namespace Vng\EvaCore\Commands\Dev;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait LogsDatabaseQueries
{
    protected $queryCount = 0;

    public function registerQueryListener()
    {
        DB::listen(function (QueryExecuted $query) {
            $this->queryCount++;

            $sql = str_replace(['%', '?'], ['%%', '%s'], $query->sql);
            $bindings = array_map(function ($binding) {
                return is_numeric($binding) ? $binding : "'{$binding}'";
            }, $query->bindings);
            $fullQuery = vsprintf($sql, $bindings);

            Log::info("Query Executed: {$fullQuery}");
            Log::info("Total Queries Executed: {$this->queryCount}");
        });
    }
}