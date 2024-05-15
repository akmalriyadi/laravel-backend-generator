<?php

namespace AkmalRiyadi\LaravelBackendGenerator\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

trait HasImage
{
    public function uploadImage($request, $fieldName, $index, $field)
    {
        if ($request->file($fieldName)[$index][$field]) {
            $image = $request->file($fieldName)[$index][$field];
            $filenamewithextension = $image->getClientOriginalName();
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
            $input = Str::slug($filename, '-') . '-' . time() . '.' . $image->getClientOriginalExtension();
            $destination = public_path('upload');
            $img = $image->move($destination, $input);
            return $input;
        }

        return null;
    }

    public function deleteImage($data)
    {
        $image_path = public_path('upload/' . $data);
        if (File::exists($image_path)) {
            return unlink($image_path);
        }
    }
}
