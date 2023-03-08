<?php

namespace Vng\EvaCore\Repositories;

use Vng\EvaCore\Http\Requests\VideoCreateRequest;
use Vng\EvaCore\Http\Requests\VideoUpdateRequest;
use Vng\EvaCore\Models\Video;

interface VideoRepositoryInterface extends InstrumentOwnedEntityRepositoryInterface
{
    public function create(VideoCreateRequest $request): Video;
    public function update(Video $download, VideoUpdateRequest $request): Video;
}
