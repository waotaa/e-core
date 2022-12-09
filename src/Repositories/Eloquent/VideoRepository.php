<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Foundation\Http\FormRequest;
use Vng\EvaCore\Http\Requests\VideoCreateRequest;
use Vng\EvaCore\Http\Requests\VideoUpdateRequest;
use Vng\EvaCore\Models\Video;
use Vng\EvaCore\Repositories\VideoRepositoryInterface;

class VideoRepository extends BaseRepository implements VideoRepositoryInterface
{
    use InstrumentOwnedEntityRepository;

    public string $model = Video::class;

    public function create(VideoCreateRequest $request): Video
    {
        return $this->saveFromRequest(new $this->model(), $request);
    }

    public function update(Video $video, VideoUpdateRequest $request): Video
    {
        return $this->saveFromRequest($video, $request);
    }

    public function saveFromRequest(Video $video, FormRequest $request): Video
    {
        $video->fill([
            'video_identifier' => $request->input('video_identifier'),
            'provider' => $request->input('provider'),
        ]);
        $video->instrument()->associate($request->input('instrument_id'));
        $video->save();
        return $video;
    }
}
