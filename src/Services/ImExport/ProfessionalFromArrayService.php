<?php

namespace Vng\EvaCore\Services\ImExport;

use Illuminate\Database\Eloquent\Model;
use Vng\EvaCore\Models\Environment;
use Vng\EvaCore\Models\Professional;
use Vng\EvaCore\Services\Cognito\CognitoService;

class ProfessionalFromArrayService extends BaseFromArrayService
{
    public function handle(): ?Model
    {
        $data = $this->data;

        if (!isset($data['environment'])) {
            return null;
        }

        /** @var Environment $environment */
        $environment = EnvironmentFromArrayService::create($data['environment']);

        /** @var Professional $professional */
        $professional = Professional::query()->firstOrNew([
            'email' => $data['email'],
            'environment_id' => $environment->id,
        ]);

        $professional->environment()->associate($environment);

        $professional = $professional->fill([
            'username' => $data['username'], // old username
            'enabled' => $data['enabled'],
            'last_seen_at' => $data['last_seen_at'],
            'email_verified' => $data['email_verified'],
            'status' => $data['status'],
        ]);

        // Quietly, we don't want cognito users yet
        $professional->saveQuietly();
        return $professional;
    }
}