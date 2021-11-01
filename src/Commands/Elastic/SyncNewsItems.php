<?php

namespace Vng\EvaCore\Commands\Elastic;

use Vng\EvaCore\Jobs\SyncResourceToElastic;
use Vng\EvaCore\Models\NewsItem;
use Illuminate\Console\Command;

class SyncNewsItems extends Command
{
    protected $signature = 'elastic:sync-news-items {--f|fresh}';
    protected $description = 'Sync all news items to ES';

    public function handle(): int
    {
        $this->getOutput()->writeln('syncing news items');
        $this->getOutput()->writeln('used index-prefix: ' . config('elastic.prefix'));

        if ($this->option('fresh')) {
            $this->call('elastic:delete-index', ['index' => 'news_items', '--force' => true]);
        }

        $this->output->writeln('');
        foreach (NewsItem::all() as $newsItem) {
            $this->getOutput()->write('.');
//            $this->getOutput()->write('- ' . $newsItem->name);
            dispatch(new SyncResourceToElastic($newsItem));
        }

        $this->output->writeln('');
        $this->output->writeln('');
        return 0;
    }
}
