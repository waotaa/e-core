<?php

namespace Vng\EvaCore\Commands\Reallocation;

use Vng\EvaCore\Services\ReallocationService;

class MoveOwnedItems extends ReallocationBaseCommand
{
    protected $signature = 'eva:move-items {currentOwner} {currentOwnerTypeOrNewOwner} {newOwner?} {newOwnerType?} {--f|force}';
    protected $description = 'Move the owners Instruments and Providers to a different owner';

    public function handle()
    {
        $input = $this->getInput();
        if (is_null($input)) {
            return 1;
        }

        ReallocationService::transferOwnership($input['current'], $input['new']);
        return 0;
    }
}
