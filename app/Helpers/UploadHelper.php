<?php


namespace App\Helpers;

use Illuminate\Http\Request;
class UploadHelper
{
    public static function uploadFile(Request $request, $fileName)
    {
        if ($request->hasFile($fileName)) {
            $file = $request->file($fileName);
            $path = $request->get('path');

            $destinationPath = public_path() . "/uploads" . $path;
            $name = preg_replace('/\s+/', '', $file->getClientOriginalName());
            $filename = time() . '_' . $name;
            if ($file->move($destinationPath, $filename)) {
                $filePath = "uploads" . $path . '/' . $filename;
                return $filePath;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }
}
