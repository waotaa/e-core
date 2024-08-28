<?php

namespace Vng\EvaCore\Jobs\Export;

use Illuminate\Support\Facades\Log;

trait useMemoryUsageTrait
{
    public function logMemoryUsage()
    {
        Log::info("Memory usage: " . $this->getMemoryUsage());
    }

    public function getMemoryUsage(): string
    {
        return $this->formatBytes(memory_get_usage());
    }

    private function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}