<?php

namespace Vng\EvaCore\Commands\Operations;

use Illuminate\Console\Command;
use Vng\EvaCore\Models\Environment;
use Vng\EvaCore\Models\NewsItem;

class AddNewsItem extends Command
{
    protected $signature = 'eva:add-news-item';
    protected $description = 'Adds a news item';

    public function handle(): int
    {
        Environment::all()->each(fn (Environment $e) => $this->addNewsItemForEnvironment($e));

//        $environmentSlug = config('eva.environment.slug');
//        if (!$environmentSlug) {
//            $this->output->warning('No environment slug found in config');
//            return 1;
//        }
//
//        /** @var Environment $environment */
//        $environment = Environment::query()->where('slug', $environmentSlug)->firstOrFail();
//        $this->addNewsItemForEnvironment($environment);

        $this->call('elastic:sync-environments', [
            '--fresh' => false,
        ]);

        return 0;
    }

    private function addNewsItemForEnvironment(Environment $environment)
    {
        $title = 'Update handleidingen Eva én een nieuwe handreiking';
        if ($title) {
            /** @var NewsItem $newsItem */
            $newsItem = NewsItem::query()->firstOrNew([
                'title' => $title,
                'environment_id' => $environment->id,
            ]);

            $newsItem->environment()->associate($environment);
            $newsItem->fill([
                'body' => '<p>Eva wordt met jullie hulp steeds beter en completer. De handleidingen voor klantmanagers en cms waren daarom toe aan een update. Ook maakte VNG een nieuwe handreiking speciaal voor instrumentbeheerders en omgevingsbeheerders.&nbsp;</p><p>&nbsp;</p><p><strong>Geüpdatet</strong></p><ul><li>Eva Handleiding cms</li><li>Eva Handleiding klantmanager</li></ul><p>&nbsp;</p><p><strong>Nieuw</strong></p><ul><li>Eva Handreiking instrumentbeheerder</li></ul><p>&nbsp;</p><p>Je vindt de handleidingen en handreiking op de website van VNG onderaan de pagina onder <strong>downloads</strong>.</p>',
                'teaser' => 'De Handleiding klantmanager en Handleiding cms hebben een update gekregen. Ook is er een nieuwe handreiking beschikbaar voor instrumentbeheerders.'
            ])->save();
        }
    }

}
