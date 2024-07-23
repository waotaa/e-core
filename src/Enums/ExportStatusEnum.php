<?php

namespace Vng\EvaCore\Enums;

use MyCLabs\Enum\Enum;

class ExportStatusEnum extends Enum
{
    private const created = 'Created';
    private const inProgress = 'In Progress';
    private const failed = 'Failed';
    private const finished = 'Finished';
}
