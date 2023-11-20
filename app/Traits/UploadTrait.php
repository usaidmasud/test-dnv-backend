<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

trait UploadTrait
{
    public function uploadOne(UploadedFile $uploadedFile, $folder = null, $filename = null, $size = null, $disk = 'public')
    {
        $name = !is_null($filename) ? $filename : Str::random(25);

        $file = $uploadedFile->storeAs($folder, $name . '.' . $uploadedFile->getClientOriginalExtension(), $disk);

        $this->resizeImage($file);
        return $file;
    }

    public function uploadBatch(array $uploadedFiles, $folder = null, $disk = 'public')
    {
        $uploadedFilesPaths = [];

        foreach ($uploadedFiles as $uploadedFile) {
            $uploadedFilesPaths[] = $this->uploadOne($uploadedFile, $folder, null, null, $disk);
        }

        return $uploadedFilesPaths;
    }

    /**
     * resizeImage
     *
     * @param  mixed $imgPath
     * @return void
     */
    public function resizeImage($imgPath)
    {
        if (Storage::exists('public/' . $imgPath)) {
            $image = Image::make(Storage::get('public/' . $imgPath))->resize(320, 320)->stream();
            Storage::disk('public')->put($imgPath, $image);
        }
    }

    /**
     * deleteOne
     *
     * @param  mixed $url
     * @return void
     */
    public function deleteOne($url)
    {
        if (Storage::exists('public/' . $url)) {
            Storage::delete('public/' . $url);
        }
    }
    /**
     * deleteMany
     *
     * @param  mixed $url
     * @return void
     */
    public function deleteMany($url)
    {
        $urls = $url->map(function ($value) {
            return 'public/' . $value;
        });
        Storage::delete($urls);
    }
}
