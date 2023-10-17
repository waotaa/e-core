<?php

namespace Vng\EvaCore\Jobs;

use Vng\EvaCore\Models\Professional;
use Vng\EvaCore\Models\Rating;

class MatchRatingToProfessionalJob extends ElasticJob
{
    public function __construct(protected Rating $rating)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $professionalEmail = $this->rating->email;
        if (!is_null($professionalEmail)) {
            $professional = Professional::query()->where('email', $professionalEmail)->first();
            if ($professional) {
                $this->rating->professional()->associate($professional);
                $this->rating->saveQuietly();
            }
        }
    }
}
