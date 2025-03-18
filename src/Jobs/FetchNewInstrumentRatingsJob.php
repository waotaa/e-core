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

        $ratings = collect($instrumentDoc['_source']['Beoordeling'] ?? $instrumentDoc['_source']['ratings'] ?? []);

        if ($ratings->isEmpty()) {
            Log::warning('No ratings for instrument', [
                'source' => $instrumentDoc['_source']
            ]);
            return;
        }

        $newRatings = $ratings->filter(fn ($r) => is_null($r['id']));
        if ($newRatings->isEmpty()) {
            Log::info('No new ratings for instrument');
            return;
        }

        Log::info('New ratings found!');

        $newRatings->each(function($rating) {
            $this->createRatingEntity($rating);
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

    private function createRatingEntity($rating): void
    {
        $createdAt = $rating['DatTijdBeoordeling'] ?? $rating['created_at'];

        $ratingModel = new Rating([
            'author' => $rating["AuteurBeoordeling"] ?? $rating["author"],
            'email' => $rating["EmailadresAuteurBeoordeling"] ?? $rating["email"],
            'general_score' => $rating["AlgemeneScore"] ?? $rating["general_score"],
            'general_explanation' => $rating["ToelAlgemeneScore"] ?? $rating["general_explanation"],
            'result_score' => $rating["ResultaatScore"] ?? $rating["result_score"],
            'result_explanation' => $rating["ToelResultaatScore"] ?? $rating["result_explanation"],
            'execution_score' => $rating["UitvoeringsScore"] ?? $rating["execution_score"],
            'execution_explanation' => $rating["ToelUitvoeringsScore"] ?? $rating["execution_explanation"],
            'created_at' => $createdAt ? new DateTime($createdAt) : null,
        ]);

        $ratingModel->instrument()->associate($this->instrument);
        $ratingModel->save();
    }
}
