<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageService
{
    protected ImageManager $manager;
    protected array $defaultSizes;
    protected int $quality;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
        $this->defaultSizes = config('images.thumbnail_sizes', [400, 800, 1200]);
        $this->quality = config('images.quality', 85);
    }

    /**
     * Upload an image, optimize it, and generate responsive thumbnails.
     *
     * @param  UploadedFile  $file
     * @param  string        $directory  Base directory inside the public disk
     * @param  int[]         $sizes      Thumbnail sizes (defaults to config)
     * @return array{original: string, thumbnails: array<int, string>}
     */
    public function upload(UploadedFile $file, string $directory, array $sizes = []): array
    {
        $sizes = $sizes ?: $this->defaultSizes;
        $filename = time() . '_' . Str::random(10) . '.webp';

        try {
            // Read and optimize the original image
            $image = $this->manager->read($file->getRealPath());

            // Store optimized original
            $originalPath = "{$directory}/original/{$filename}";
            $encoded = $image->toWebp($this->quality);
            Storage::disk('public')->put($originalPath, (string)$encoded);

            // Generate thumbnails
            $thumbnails = [];
            foreach ($sizes as $size) {
                $thumb = $this->manager->read($file->getRealPath());
                $thumb->cover($size, $size);

                $thumbPath = "{$directory}/thumbnails/{$size}/{$filename}";
                $encodedThumb = $thumb->toWebp($this->quality);
                Storage::disk('public')->put($thumbPath, (string)$encodedThumb);

                $thumbnails[$size] = $thumbPath;
            }

            return [
                'original' => $originalPath,
                'thumbnails' => $thumbnails,
            ];
        }
        catch (\Exception $e) {
            Log::error('Image upload failed', [
                'directory' => $directory,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Upload multiple images.
     *
     * @param  UploadedFile[]  $files
     * @param  string          $directory
     * @param  int[]           $sizes
     * @return array<int, array{original: string, thumbnails: array<int, string>}>
     */
    public function uploadMultiple(array $files, string $directory, array $sizes = []): array
    {
        $results = [];
        foreach ($files as $file) {
            $results[] = $this->upload($file, $directory, $sizes);
        }
        return $results;
    }

    /**
     * Delete an image and all its thumbnails.
     *
     * @param  string|null  $path  Path relative to the public disk (original path)
     * @return bool
     */
    public function delete(?string $path): bool
    {
        if (empty($path)) {
            return true;
        }

        try {
            // Delete original
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            // Derive thumbnail paths and delete them
            $filename = basename($path);
            $directory = dirname(dirname($path)); // strip /original

            foreach ($this->defaultSizes as $size) {
                $thumbPath = "{$directory}/thumbnails/{$size}/{$filename}";
                if (Storage::disk('public')->exists($thumbPath)) {
                    Storage::disk('public')->delete($thumbPath);
                }
            }

            return true;
        }
        catch (\Exception $e) {
            Log::warning('Image deletion failed', [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Delete multiple images and their thumbnails.
     *
     * @param  array  $paths
     * @return bool
     */
    public function deleteMultiple(array $paths): bool
    {
        $success = true;
        foreach ($paths as $path) {
            if (!$this->delete($path)) {
                $success = false;
            }
        }
        return $success;
    }

    /**
     * Get the full URL for an image, optionally at a specific thumbnail size.
     *
     * @param  string|null  $path  Path relative to the public disk
     * @param  int|null     $size  Thumbnail size (null = original)
     * @return string|null
     */
    public function getUrl(?string $path, ?int $size = null): ?string
    {
        if (empty($path)) {
            return null;
        }

        // If a thumbnail size is requested, try to resolve the thumbnail path
        if ($size !== null) {
            $thumbPath = $this->resolveThumbnailPath($path, $size);
            if ($thumbPath) {
                // Check locally first, but always trust the path if it looks valid
                if (Storage::disk('public')->exists($thumbPath)) {
                    return url('storage/' . $thumbPath);
                }
                // On production, the file may exist even if disk check fails
                // (e.g. symlink differences). Return URL anyway.
                return url('storage/' . $thumbPath);
            }
        }

        // Always return a URL for any non-empty path — the file exists on the server
        // Storage::exists() can return false on production due to symlink/disk config differences
        return url('storage/' . $path);
    }

    /**
     * Get URLs for all responsive sizes of an image.
     *
     * @param  string|null  $path
     * @return array<string, string|null>
     */
    public function getResponsiveUrls(?string $path): array
    {
        if (empty($path)) {
            return [];
        }

        $urls = ['original' => $this->getUrl($path)];

        foreach ($this->defaultSizes as $size) {
            $urls[(string)$size] = $this->getUrl($path, $size);
        }

        return $urls;
    }

    /**
     * Resolve a thumbnail path from the original path for a given size.
     *
     * Original: {dir}/original/{file}  →  {dir}/thumbnails/{size}/{file}
     * Legacy:   {dir}/{file}            →  null (no thumbnail exists)
     */
    protected function resolveThumbnailPath(string $originalPath, int $size): ?string
    {
        // New structure: {dir}/original/{file}
        if (str_contains($originalPath, '/original/')) {
            return str_replace('/original/', "/thumbnails/{$size}/", $originalPath);
        }

        // Legacy path — no thumbnail directory structure
        return null;
    }
}
