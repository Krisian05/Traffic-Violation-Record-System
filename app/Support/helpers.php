<?php

use Illuminate\Support\Facades\Storage;

if (! function_exists('uploads_disk')) {
    function uploads_disk(): string
    {
        return config('filesystems.uploads_disk', 'public');
    }
}

if (! function_exists('uploaded_file_url')) {
    function uploaded_file_url(?string $path): string
    {
        if (blank($path)) {
            return '';
        }

        return Storage::disk(uploads_disk())->url($path);
    }
}
