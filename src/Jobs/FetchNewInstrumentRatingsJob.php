<?php

namespace Vng\EvaCore\Jobs;

use Vng\EvaCore\ElasticResources\RatingResource;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Models\Professional;
use Vng\EvaCore\Models\Rating;
use DateTime;

class FetchNewInstrumentRatingsJob extends ElasticJob
{
    private Instrument $instrument;

    public function __construct(Instrument $instrument)
    {
        parent::__construct();
        $this->instrument = $instrument;
    }

    public function handle(): void
    {
        $elasticSearchClient = $this->getClient();

        $index = 'instruments';
        $prefixedIndex = $this::prefixIndex($index);

        $indexExists = $elasticSearchClient->indices()->exists([
            'index' => $prefixedIndex
        ]);
        if (!$indexExists) {
            return;
        }

        $exists = $elasticSearchClient->exists([
            'index' => $prefixedIndex,
            'id' => $this->instrument->uuid,
        ]);
        if (!$exists) {
            return;
        }

        $instrumentDoc = $elasticSearchClient->get([
            'index' => $prefixedIndex,
            'id' => $this->instrument->uuid,
        ]);

        $ratings = collect($instrumentDoc['_source']['ratings']);

        if ($ratings->isNotEmpty()) {
            $newRatings = $ratings->filter(fn ($r) => is_null($r['id']));
            $newRatings->each(function($rating) {
                $ratingModel = new Rating([
                    'author' => $rating["author"],
                    'email' => $rating["professional"]["email"],
                    'general_score' => $rating["general_score"],
                    'general_explanation' => $rating["general_explanation"],
                    'result_score' => $rating["result_score"],
                    'result_explanation' => $rating["result_explanation"],
                    'execution_score' => $rating["execution_score"],
                    'execution_explanation' => $rating["execution_explanation"],

                    'created_at' => new DateTime($rating['created_at']),
                ]);
                $ratingModel->instrument()->associate($this->instrument);

                if ($rating["professional"]["username"]) {
                    $professional = Professional::query()->where('username', $rating["professional"]["username"])->first();
                    if ($professional) {
                        $ratingModel->professional()->associate($professional);
                    }
                }

                $ratingModel->saveQuietly();
            });

            // Update the ratings field on the instrument document
            $elasticSearchClient->update([
                'index' => $prefixedIndex,
                'id' => $this->instrument->uuid,
                'body' => [
                    'doc' => [
                        'ratings' => RatingResource::many($this->instrument->fresh()->ratings),
                    ]
                ]
            ]);
        }
    }
}
