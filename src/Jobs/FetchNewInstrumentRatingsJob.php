<?php

namespace Vng\EvaCore\Jobs;

use DateTime;
use Illuminate\Support\Facades\Log;
use Vng\EvaCore\ElasticResources\Original\RatingResource;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Models\Rating;

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
            Log::warning('Index not found');
            return;
        }

        $exists = $elasticSearchClient->exists([
            'index' => $prefixedIndex,
            'id' => $this->instrument->uuid,
        ]);
        if (!$exists) {
            Log::warning('Document not found');
            return;
        }

        try {
            $instrumentDoc = $elasticSearchClient->get([
                'index' => $prefixedIndex,
                'id' => $this->instrument->uuid,
            ]);
        } catch (\Elasticsearch\Common\Exceptions\Missing404Exception $e) {
            Log::warning('Document not found');
            return;
        }

        $ratings = collect($instrumentDoc['_source']['Beoordeling'] ?? []);

        if ($ratings->isEmpty()) {
            Log::warning('No ratings for instrument');
            return;
        }

        $newRatings = $ratings->filter(fn ($r) => is_null($r['id']));
        if ($newRatings->isEmpty()) {
            Log::info('No new ratings for instrument');
            return;
        }

        $newRatings->each(function($rating) {
            $ratingModel = new Rating([
                'author' => $rating["AuteurBeoordeling"],
                'email' => $rating["EmailadresAuteurBeoordeling"],
                'general_score' => $rating["AlgemeneScore"],
                'general_explanation' => $rating["ToelAlgemeneScore"],
                'result_score' => $rating["ResultaatScore"],
                'result_explanation' => $rating["ToelResultaatScore"],
                'execution_score' => $rating["UitvoeringsScore"],
                'execution_explanation' => $rating["ToelUitvoeringsScore"],

                'created_at' => new DateTime($rating['DatTijdBeoordeling']),
            ]);
            $ratingModel->instrument()->associate($this->instrument);

            $ratingModel->saveQuietly();
        });

        // Update the Beoordeling field on the instrument document
        $elasticSearchClient->update([
            'index' => $prefixedIndex,
            'id' => $this->instrument->uuid,
            'body' => [
                'doc' => [
                    'Beoordeling' => RatingResource::many($this->instrument->fresh()->ratings),
                ]
            ]
        ]);
    }
}
