<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Vng\EvaCore\Http\Requests\ReleaseChangeCreateRequest;
use Vng\EvaCore\Http\Requests\ReleaseChangeUpdateRequest;
use Vng\EvaCore\Models\ReleaseChange;
use Vng\EvaCore\Repositories\ReleaseChangeRepositoryInterface;
use Vng\EvaCore\Repositories\ReleaseRepositoryInterface;
use Vng\EvaCore\Services\Storage\ReleaseImageStorageService;

class ReleaseChangeRepository extends BaseRepository implements ReleaseChangeRepositoryInterface
{
    public string $model = ReleaseChange::class;

    public function create(ReleaseChangeCreateRequest $request): ReleaseChange
    {
        return $this->saveFromRequest(new $this->model(), $request);
    }

    public function update(ReleaseChange $releaseChange, ReleaseChangeUpdateRequest $request): ReleaseChange
    {
        return $this->saveFromRequest($releaseChange, $request);
    }

    public function saveFromRequest(ReleaseChange $releaseChange, FormRequest $request): ReleaseChange
    {
        $releaseRepository = app(ReleaseRepositoryInterface::class);

        $release = $releaseRepository->find($request->input('release_id'));
        if (is_null($release)) {
            throw new \Exception('invalid release provided');
        }
        $releaseChange->release()->associate($release);


        $image = $request->input('image');
        if ($request->hasFile('image')) {
            /** @var UploadedFile $uploadedFile */
            $image = $request->file('image');
            // Check if the file actually exists on the server
            if ($image->isValid()) {
                $storedFile = ReleaseImageStorageService::make()
                    ->setRelease($release)
                    ->storeFile($image);
                $imagePath = $storedFile->getPath();
                $releaseChange->fill([
                    'image' => $imagePath
                ]);
            }
        } elseif (!is_null($image)) {
            $releaseChange->fill([
                'image' => $image
            ]);
        }

        $releaseChange->fill([
            'title' => $request->input('title'),
            'description' => $request->input('description')
        ]);

        $releaseChange->save();
        return $releaseChange;
    }
}
