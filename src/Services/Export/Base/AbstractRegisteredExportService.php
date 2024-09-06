<?php

namespace Vng\EvaCore\Services\Export\Base;

use Exception;
use Illuminate\Support\Facades\App;
use Vng\EvaCore\Enums\ExportStatusEnum;
use Vng\EvaCore\Models\Export;

/**
 * This class is an extension of the default AbstractEntityExportService
 * This class adds the functionality of registering the export progress in the Export entity
 */
abstract class AbstractRegisteredExportService extends AbstractEntityExportService
{

}