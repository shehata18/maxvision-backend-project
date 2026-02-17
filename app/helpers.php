<?php

use App\Services\ImageService;

if (!function_exists('image_url')) {
    /**
     * Get the full URL for an image, optionally at a specific thumbnail size.
     *
     * @param  string|null  $path  Path relative to the public disk
     * @param  int|null     $size  Thumbnail size (null = original)
     * @return string|null
     */
    function image_url(?string $path, ?int $size = null): ?string
    {
        return app(ImageService::class)->getUrl($path, $size);
    }
}
