<?php

namespace App\Services\Common;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class FileService
{

    public function uploadPhoto($model, UploadedFile $newPhoto): string
    {
        $modelClass = get_class($model);

        if (strpos($modelClass, 'App\\Models\\') !== 0) {
            throw new \Exception('The model must be in the App\Models namespace.');
        }

        if ($model->photo) {
            $this->deletePhoto($model->photo);
        }

        $modelName = Str::lower(class_basename($model));

        $folderName = "{$modelName}_photos";

        return $newPhoto->store($folderName, 'public');
    }

    public function deletePhoto(string $path): void
    {
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
