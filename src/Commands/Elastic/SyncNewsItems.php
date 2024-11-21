<?php

namespace Vng\EvaCore\Commands\Elastic;

use Vng\EvaCore\Jobs\SyncSearchableModelToElasticJob;
use Vng\EvaCore\Models\NewsItem;
use Illuminate\Console\Command;
use Vng\EvaCore\Repositories\NewsItemRepositoryInterface;
use Vng\EvaCore\Services\ElasticSearch\ElasticsearchEndpointService;

class SyncNewsItems extends Command
{
    protected $signature = 'elastic:sync-news-items {--f|fresh}';
    protected $description = 'Sync all news items to ES';

    public function handle(): int
    {
        $this->getOutput()->writeln('syncing news items');
        $this->output->writeln('');
        $index = 'news_items';

        if ($this->option('fresh')) {
            $this->call('elastic:delete-index', ['index' => $index, '--force' => true]);
        }

        $fullIndex = $index;
        $prefix = config('elastic.prefix');
        if ($prefix) {
            $this->output->writeln("used index-prefix: {$prefix}");
            $fullIndex = $prefix . '-' . $index;
        }
        $this->output->writeln("used index: {$fullIndex}");

        if (!ElasticsearchEndpointService::make()->indexExists($fullIndex)) {
            $this->call(CreateIndex::class, [
                'index' => $index
            ]);
        }

        /** @var NewsItemRepositoryInterface $newsItemRepository */
        $newsItemRepository = app(NewsItemRepositoryInterface::class);
        $newsItems = $newsItemRepository
            ->builder()
            ->with([
                'environment'
            ])
            ->get();

        foreach ($newsItems as $newsItem) {
            $this->getOutput()->write('.');
//            $this->getOutput()->write('- ' . $newsItem->name);
            dispatch(new SyncSearchableModelToElasticJob($newsItem));
        }

        $this->output->newLine(2);
        $this->output->writeln('syncing news items finished!');
        return 0;
    }
}
