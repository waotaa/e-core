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

        $ratings = collect($instrumentDoc['_source']['ratings'] ?? []);
        if ($ratings->isEmpty()) {
            Log::warning('No ratings for instrument');
        }

        if ($ratings->isNotEmpty()) {
            $newRatings = $ratings->filter(fn ($r) => is_null($r['id']));
            $newRatings->each(function($rating) {
                $ratingModel = new Rating([
                    'author' => $rating["author"],
                    'email' => $rating["email"],
//                    'email' => $rating["professional"]["email"],
                    'general_score' => $rating["general_score"],
                    'general_explanation' => $rating["general_explanation"],
                    'result_score' => $rating["result_score"],
                    'result_explanation' => $rating["result_explanation"],
                    'execution_score' => $rating["execution_score"],
                    'execution_explanation' => $rating["execution_explanation"],

                    'created_at' => new DateTime($rating['created_at']),
                ]);
                $ratingModel->instrument()->associate($this->instrument);

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

//        $ratings = collect($instrumentDoc['_source']['Beoordeling'] ?? []);
//
//        if ($ratings->isEmpty()) {
//            Log::warning('No ratings for instrument');
//            return;
//        }
//        $newRatings = $ratings->filter(fn ($r) => is_null($r['id']));
//        $newRatings->each(function($rating) {
//            $ratingModel = new Rating([
//                'author' => $rating["AuteurBeoordeling"],
//                'email' => $rating["EmailadresAuteurBeoordeling"],
//                'general_score' => $rating["AlgemeneScore"],
//                'general_explanation' => $rating["ToelAlgemeneScore"],
//                'result_score' => $rating["ResultaatScore"],
//                'result_explanation' => $rating["ToelResultaatScore"],
//                'execution_score' => $rating["UitvoeringsScore"],
//                'execution_explanation' => $rating["ToelUitvoeringsScore"],
//
//                'created_at' => new DateTime($rating['DatTijdBeoordeling']),
//            ]);
//            $ratingModel->instrument()->associate($this->instrument);
//
//            $ratingModel->saveQuietly();
//        });
//
//        // Update the ratings field on the instrument document
//        $elasticSearchClient->update([
//            'index' => $prefixedIndex,
//            'id' => $this->instrument->uuid,
//            'body' => [
//                'doc' => [
//                    'Beoordeling' => RatingResource::many($this->instrument->fresh()->ratings),
//                ]
//            ]
//        ]);
    }
}
