<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class UploadUrl
{
    public static function public(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '/storage/')) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }
}
