<?php

namespace Vng\EvaCore\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Vng\EvaCore\Jobs\ElasticJob;

class LogJobPayloadSize
{
    /**
     * Handle an incoming job.
     *
     * @param  mixed  $job
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($job, $next)
    {
        // Controleer of de job een object is
        if ($job instanceof ElasticJob) {
            if (property_exists($job, 'job') && method_exists($job->job, 'payload')) {
                // Haal de payload op van de job
                $payload = $job->job->payload();

                // Bepaal de grootte van de payload in bytes
                $sizeInBytes = strlen(serialize($payload));

                // Converteer de grootte naar kilobytes (KB)
                $sizeInKB = $sizeInBytes / 1024;

                // Log de grootte van de payload in KB
                Log::info('>>> Job payload size: ' . number_format($sizeInKB, 2) . ' KB');

                // Voeg een waarschuwing toe als de payload de SQS-limiet van 256 KB overschrijdt
                if ($sizeInKB > 256) {
                    Log::warning('>>> Job payload size exceeds the SQS limit of 256 KB: ' . number_format($sizeInKB, 2) . ' KB');
                }
            }
        }

        return $next($job);
    }
}