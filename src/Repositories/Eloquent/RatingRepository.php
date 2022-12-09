<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Foundation\Http\FormRequest;
use Vng\EvaCore\Http\Requests\RatingCreateRequest;
use Vng\EvaCore\Http\Requests\RatingUpdateRequest;
use Vng\EvaCore\Models\Rating;
use Vng\EvaCore\Repositories\RatingRepositoryInterface;

class RatingRepository extends BaseRepository implements RatingRepositoryInterface
{
    use InstrumentOwnedEntityRepository;

    public string $model = Rating::class;

    public function create(RatingCreateRequest $request): Rating
    {
        return $this->saveFromRequest(new $this->model(), $request);
    }

    public function update(Rating $rating, RatingUpdateRequest $request): Rating
    {
        return $this->saveFromRequest($rating, $request);
    }

    public function saveFromRequest(Rating $rating, FormRequest $request): Rating
    {
        $rating->fill([
            'author' => $request->input('author'),
            'email' => $request->input('email'),
            'general_score' => $request->input('general_score'),
            'general_explanation' => $request->input('general_explanation'),
            'result_score' => $request->input('result_score'),
            'result_explanation' => $request->input('result_explanation'),
            'execution_score' => $request->input('execution_score'),
            'execution_explanation' => $request->input('execution_explanation'),
        ]);

        $rating->instrument()->associate($request->input('instrument_id'));

        if ($request->has('professional_id')) {
            $rating->professional()->associate($request->input('professional_id'));
        }

        $rating->save();
        return $rating;
    }
}
