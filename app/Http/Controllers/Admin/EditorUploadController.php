<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaAsset;
use App\Support\UploadUrl;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EditorUploadController extends Controller
{
    public function image(Request $request): JsonResponse
    {
        $data = $request->validate([
            'image' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:5120'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:1000'],
        ]);

        $file = $request->file('image');
        $path = $file->store('editor-images', 'public');

        $asset = MediaAsset::create([
            'name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize() ?: 0,
            'alt_text' => $data['alt_text'] ?? null,
            'caption' => $data['caption'] ?? null,
            'uploaded_by' => $request->user()->id,
        ]);

        return response()->json([
            'id' => $asset->id,
            'url' => UploadUrl::public($path),
            'name' => $asset->name,
            'alt_text' => $asset->alt_text,
            'caption' => $asset->caption,
        ]);
    }
}
