<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'type' => 'required|in:avatar,post,badge'
        ]);

        $file = $request->file('file');
        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

        $path = $this->getPath($request->type);
        $filePath = $file->storeAs($path, $fileName, 'public');

        return response()->json([
            'path' => $filePath,
            'url' => Storage::url($filePath)
        ]);
    }

    private function getPath($type)
    {
        switch ($type) {
            case 'avatar':
                return 'avatars';
            case 'post':
                return 'post-images';
            case 'badge':
                return 'badge-icons';
            default:
                return 'misc';
        }
    }
}