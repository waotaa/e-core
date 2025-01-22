<?php

namespace Vng\EvaCore\Repositories;

use Vng\EvaCore\Http\Requests\FaqCreateRequest;
use Vng\EvaCore\Http\Requests\FaqUpdateRequest;
use Vng\EvaCore\Models\Faq;

interface FaqRepositoryInterface extends BaseRepositoryInterface
{
    public function create(FaqCreateRequest $request): Faq;
    public function update(Faq $faq, FaqUpdateRequest $request): Faq;
}
