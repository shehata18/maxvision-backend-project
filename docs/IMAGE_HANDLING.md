# Image Handling System

This document describes the image handling system for the MaxVision backend, including uploading, optimization, responsive thumbnails, and cleanup.

## Overview

The system uses **Intervention Image v3** with the GD driver to handle image uploads, automatic optimization, and responsive thumbnail generation. All images are stored in the `public` disk (`storage/app/public/`) and served via the `/storage` symlink.

## Storage Structure

```
storage/app/public/
├── products/
│   ├── original/              # Full-size optimized originals
│   │   └── 1708123456_abc123.webp
│   └── thumbnails/
│       ├── 400/               # 400×400 thumbnails
│       ├── 800/               # 800×800 thumbnails
│       └── 1200/              # 1200×1200 thumbnails
├── products/gallery/
│   ├── original/
│   └── thumbnails/
│       ├── 400/
│       ├── 800/
│       └── 1200/
├── solutions/
│   ├── original/
│   └── thumbnails/
└── case-studies/
    ├── original/
    └── thumbnails/
```

## Configuration

All image settings are defined in `config/images.php`:

| Setting | Default | Description |
|---------|---------|-------------|
| `thumbnail_sizes` | `[400, 800, 1200]` | Pixel dimensions for generated thumbnails |
| `quality` | `85` | WebP output quality (0–100) |
| `max_upload_size` | `5120` | Max upload size in KB (5 MB) |
| `allowed_types` | `jpeg, png, webp` | Accepted MIME types |
| `directories` | See file | Base directories per entity |

## ImageService API

The core service is `App\Services\ImageService`.

### `upload(UploadedFile $file, string $directory, array $sizes = []): array`

Uploads and optimizes an image, generating thumbnails for each configured size.

**Returns:**
```php
[
    'original' => 'products/original/1708123456_abc123.webp',
    'thumbnails' => [
        400 => 'products/thumbnails/400/1708123456_abc123.webp',
        800 => 'products/thumbnails/800/1708123456_abc123.webp',
        1200 => 'products/thumbnails/1200/1708123456_abc123.webp',
    ]
]
```

### `delete(?string $path): bool`

Deletes an original image and all associated thumbnails.

### `getUrl(?string $path, ?int $size = null): ?string`

Returns the full URL for an image. Supports both legacy flat paths and the new `original/thumbnails` structure.

### `getResponsiveUrls(?string $path): array`

Returns URLs for all available sizes:
```php
[
    'original' => 'http://localhost:8000/storage/products/original/img.webp',
    '400' => 'http://localhost:8000/storage/products/thumbnails/400/img.webp',
    '800' => 'http://localhost:8000/storage/products/thumbnails/800/img.webp',
    '1200' => 'http://localhost:8000/storage/products/thumbnails/1200/img.webp',
]
```

### Global Helper

```php
image_url('products/original/img.webp');       // Full URL
image_url('products/original/img.webp', 400);  // 400px thumbnail URL
```

## Model Accessors

All image-bearing models (`Product`, `Solution`, `CaseStudy`) expose these accessors:

| Accessor | Returns | Description |
|----------|---------|-------------|
| `image_url` | `?string` | Full URL of the main image |
| `image_responsive` | `array` | URLs for all thumbnail sizes |

Product also has:

| Accessor | Returns | Description |
|----------|---------|-------------|
| `gallery_urls` | `array` | Full URLs for all gallery images |
| `gallery_responsive` | `array` | Responsive URLs per gallery image |

## API Response Format

All API resources include responsive image data:

```json
{
  "image": "http://localhost:8000/storage/products/img.webp",
  "imageResponsive": {
    "original": "http://localhost:8000/storage/products/img.webp",
    "400": "http://localhost:8000/storage/products/thumbnails/400/img.webp",
    "800": "http://localhost:8000/storage/products/thumbnails/800/img.webp",
    "1200": "http://localhost:8000/storage/products/thumbnails/1200/img.webp"
  }
}
```

## Filament Upload Configuration

All Filament `FileUpload` fields are configured with:
- `->optimize('webp')` — Auto-converts uploads to WebP
- `->resize(1920)` — Caps maximum dimensions at 1920px
- `->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])` — Restricts to safe formats
- `->maxSize(5120)` — 5 MB upload limit
- `->imageEditor()` — Built-in crop/edit UI

## Cleanup on Deletion

Model observers handle automatic cleanup:
- **ProductObserver** — Deletes image + all gallery images + thumbnails
- **SolutionObserver** — Deletes image + thumbnails, clears caches
- **CaseStudyObserver** — Deletes image + thumbnails, clears caches

## Optimizing Existing Images

To generate thumbnails for images already in the database:

```bash
php artisan images:optimize
```

This command:
1. Scans all products, solutions, and case studies with images
2. Skips images that already have thumbnails
3. Generates 400, 800, and 1200px WebP thumbnails
4. Shows progress bars and a summary

## Troubleshooting

### Images not loading
1. Ensure the storage symlink exists: `php artisan storage:link`
2. Check `APP_URL` in `.env` matches your server URL
3. Verify the file exists in `storage/app/public/`

### Thumbnails not generating
1. Ensure the GD PHP extension is installed: `php -m | grep gd`
2. Check file permissions on `storage/app/public/`
3. Review `storage/logs/laravel.log` for errors

### Legacy images showing as null
Legacy images (flat paths like `products/image.jpg`) are still supported. The `getUrl()` method falls back gracefully. Run `php artisan images:optimize` to generate thumbnails for them.
