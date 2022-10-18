<?php

namespace Vng\EvaCore\Services\ImExport;

use Illuminate\Database\Eloquent\Model;
use Vng\EvaCore\Models\Environment;
use Vng\EvaCore\Models\NewsItem;

class EnvironmentFromArrayService extends BaseFromArrayService
{
    public function handle(): Model
    {
        $data = $this->data;

        /** @var Environment $environment */
        $environment = Environment::query()->firstOrNew([
            'slug' => $data['slug']
        ]);

        $environment = $environment->fill($data);

        if (isset($data['contact'])) {
            $contact = ContactFromArrayService::create($data['contact']);
            $environment->contact()->associate($contact);
        }

        $environment->saveQuietly();

        $this->attachFeaturedOrganisations($environment);
        $this->createNewsItems($environment);

        return $environment;
    }

    public function attachFeaturedOrganisations(Environment $environment): Environment
    {
        if (isset($data['featured_organisations'])) {
            $organsiations = $data['featured_organisations'];
            foreach ($organsiations as $organsiationData) {
                $organisation = OrganisationFromArrayService::create($organsiationData);
                $environment->featuredOrganisations()->attach($organisation);
            }
        }
        return $environment;
    }

    public function createNewsItems(Environment $environment): Environment
    {
        if (isset($data['news_items'])) {
            $newsItems = $data['news_items'];
            foreach ($newsItems as $newsItemData) {
                /** @var NewsItem $newsItem */
                $newsItem = NewsItemFromArrayService::create($newsItemData);

//                $newsItem->environment()->associate($environment);
//                $newsItem->saveQuietly();
            }
        }
        return $environment;
    }
}