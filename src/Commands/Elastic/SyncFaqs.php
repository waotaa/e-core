<?php

namespace Vng\EvaCore\Commands\Elastic;

use Illuminate\Console\Command;
use Vng\EvaCore\Jobs\SyncSearchableModelToElasticJob;
use Vng\EvaCore\Models\SyncAttempt;
use Vng\EvaCore\Repositories\FaqRepositoryInterface;
use Vng\EvaCore\Services\ElasticSearch\ElasticsearchEndpointService;

class SyncFaqs extends Command
{
    protected $signature = 'elastic:sync-faqs {--f|fresh}';
    protected $description = 'Sync all FAQ\'s to ES';

    public function handle(): int
    {
        $this->output->writeln('syncing FAQs...');
        $index = 'faqs';
        $this->output->writeln('');

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

        /** @var FaqRepositoryInterface $faqRepository */
        $faqRepository = app(FaqRepositoryInterface::class);
        $faqs = $faqRepository
            ->all();

        $this->output->writeln($faqs->count() . ' faqs found');
        $this->output->writeln('');

        foreach ($faqs as $faq) {
            $this->output->write('.');
//            $this->getOutput()->write('- ' . $faq->question);

            $attempt = new SyncAttempt();
            $attempt->action = 'index';
            $attempt->resource()->associate($faq);
            $attempt->save();

            dispatch(new SyncSearchableModelToElasticJob($faq, $attempt));
        }

        $this->output->newLine(2);
        $this->output->writeln('syncing faqs finished!');
        return 0;
    }
}

