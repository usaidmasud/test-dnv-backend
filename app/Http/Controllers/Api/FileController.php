<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileRequest;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FileController extends Controller
{
    use UploadTrait;

    public function upload(Request $request)
    {
        $request->validate([
            'files.*' => 'required|mimes:jpg,jpeg,png,gif|max:2048',
        ]);
        $uploadedFiles = [];
        foreach ($request->file('files') as $file) {
            if ($file->isValid()) {
                $fileName = $this->uploadOne($file);
                $uploadedFiles[] = $fileName;
            }
        }
        return response()->json(['files' => $uploadedFiles]);
    }
}
