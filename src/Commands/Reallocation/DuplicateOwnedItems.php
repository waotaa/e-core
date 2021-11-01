<?php

namespace Vng\EvaCore\Commands\Reallocation;

use Vng\EvaCore\Services\ReallocationService;

class DuplicateOwnedItems extends ReallocationBaseCommand
{
    protected $signature = 'eva:duplicate-items {currentOwner} {currentOwnerTypeOrNewOwner} {newOwner?} {newOwnerType?} {--f|force}';
    protected $description = 'Copy the owners Instruments and Providers to a different owner';

    public function handle()
    {
        $input = $this->getInput();
        if (is_null($input)) {
            return 1;
        }

        ReallocationService::copyOwnedItems($input['current'], $input['new']);
        return 0;
    }
}
