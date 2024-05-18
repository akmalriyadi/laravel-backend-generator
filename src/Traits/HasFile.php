<?php

namespace AkmalRiyadi\LaravelBackendGenerator\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

trait HasFile
{
    public function uploadImageV1($request, $fieldName, $path = 'upload', $customFieldName = null)
    {
        if ($request->file($fieldName)) {
            $image = $request->file($fieldName);
            $filenamewithextension = $image->getClientOriginalName();
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
            $filename = Str::slug($filename, '-') . '-' . time();
            if ($customFieldName) {
                $filename = Str::slug($customFieldName);
            }
            $input = $filename . '.' . $image->getClientOriginalExtension();
            $destination = public_path($path);
            $img = $image->move($destination, $input);
            return $input;
        }

        return $request->$fieldName;
    }
    public function uploadImageV2($request, $fieldName, $index, $field, $path = 'upload')
    {
        if ($request->file($fieldName)[$index][$field]) {
            $image = $request->file($fieldName)[$index][$field];
            $filenamewithextension = $image->getClientOriginalName();
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
            $input = Str::slug($filename, '-') . '-' . time() . '.' . $image->getClientOriginalExtension();
            $destination = public_path($path);
            $img = $image->move($destination, $input);
            return $input;
        }

        return null;
    }

    public function deleteImage($data, $path = 'upload')
    {
        $image_path = public_path($path . '/' . $data);
        if (File::exists($image_path)) {
            return unlink($image_path);
        }
    }

    public function rename($data, $title, $path = 'upload')
    {
        $image_path = public_path($path . '/' . $data);
        $title = Str::slug($title, '-');
        if (File::exists($image_path)) {
            $extension = pathinfo($image_path, PATHINFO_EXTENSION);
            $fileName = $title . '.' . $extension;
            $new_path = public_path('upload/' . $title . '.' . $extension);
            File::move($image_path, $new_path);
            return $fileName;
        }
    }
}
