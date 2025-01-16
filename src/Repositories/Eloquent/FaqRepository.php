<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Foundation\Http\FormRequest;
use Vng\EvaCore\Http\Requests\FaqCreateRequest;
use Vng\EvaCore\Http\Requests\FaqUpdateRequest;
use Vng\EvaCore\Models\Faq;
use Vng\EvaCore\Repositories\FaqRepositoryInterface;

class FaqRepository extends BaseRepository implements FaqRepositoryInterface
{
    public string $model = Faq::class;

    public function create(FaqCreateRequest $request): Faq
    {
        return $this->saveFromRequest(new $this->model(), $request);
    }

    public function update(Faq $faq, FaqUpdateRequest $request): Faq
    {
        return $this->saveFromRequest($faq, $request);
    }

    public function saveFromRequest(Faq $faq, FormRequest $request): Faq
    {
        $faq->fill([
            'question' => $request->input('question'),
            'answer' => $request->input('answer'),
            'type' => $request->input('type'),
            'category' => $request->input('category'),
        ]);
        $faq->save();
        return $faq;
    }
}
